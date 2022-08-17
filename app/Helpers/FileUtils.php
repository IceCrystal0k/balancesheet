<?php
namespace App\Helpers;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class FileUtils
{
    /**
     * store user uploaded file; if no file is specified, delete the current existing file (if any specified)
     * @param object $request -> UI request
     * @param number $fileId -> id of the UI field
     * @param string $storePath -> path where to store the file
     * @param array options : $removeFileName -> file name to remove if there is no request file
     *                        $newFileName -> if set, it will save the file as $newFileName.$originalFileExtension
     * @return name of the saved file if a file was specified, otherwise null
     */
    public static function storeRequestFile($request, $fileId, $storePath, $options = null)
    {
        if (!$request->hasFile($fileId)) {
            if ($options && isset($options['removeFileName']) && $options['removeFileName']) {
                Storage::disk('local')->delete($storePath . $options['removeFileName']);
            }
            return null;
        }
        $requestFile = $request->file($fileId);
        $extension = $requestFile->getClientOriginalExtension();
        $filenameWithExt = $requestFile->getClientOriginalName();
        if ($options && isset($options['newFileName'])) {
            $filenameWithExt = $options['newFileName'] . '.' . $extension;
        }

        $requestFile->storePubliclyAs($storePath, $filenameWithExt);

        return $filenameWithExt;
    }

    /**
     * delete specified media file and the cache files
     * @param string $mediaFile -> mediaFile entity
     * @param string $storePath -> path where to store the file
     */
    public static function deleteMediaFile($mediaFile, $storePath)
    {
        Storage::disk('local')->delete($storePath . $mediaFile->file);
        // also delete cache file for images, if any, using the * wildcard
        self::deleteMediaFileCache($mediaFile, $storePath);
    }

    /**
     * delete specified media cache files
     * @param string $mediaFile -> mediaFile entity
     * @param string $storePath -> path where to store the file
     */
    public static function deleteMediaFileCache($mediaFile, $storePath)
    {
        $cacheFileBaseName = $mediaFile->slug . '-' . $mediaFile->id;
        if (self::getFileType($mediaFile->extension) === 'image') {
            // use the File class, to be able to use wildcard when delete, since storage::disk doesn't support it
            File::delete(File::glob(storage_path('app/' . $storePath . 'cache/' . $cacheFileBaseName . '*.' . $mediaFile->extension)));
            // Storage::disk('local')->delete($storePath . 'cache/'.$fileInfo['filename'].'*.'.$fileInfo['extension']);
        }
    }

    /**
     * move the file and the cache files, for the specified mediaFile entity, from source folder to destination folder
     * @param string $mediaFile -> mediaFile entity
     * @param string $srcPath -> path from where to move the file
     * @param string $dstPath -> path where to move the file
     * @param string $initialSlug -> initial slug of the media file
     */
    public static function moveMediaFile($mediaFile, $srcPath, $dstPath, $initialSlug)
    {
        // move media file to new destination
        Storage::disk('local')->move($srcPath . $mediaFile->file, $dstPath . $mediaFile->file);
        self::moveMediaFileCache($mediaFile, $srcPath, $dstPath, $initialSlug);
    }

    /**
     * rename the cache files, for the specified mediaFile entity, within the same folder
     * @param string $mediaFile -> mediaFile entity
     * @param string $srcPath -> path from where to move the file
     * @param string $initialSlug -> initial slug of the media file
     */
    public static function renamMediaFileCache($mediaFile, $srcPath, $initialSlug)
    {
        self::moveMediaFileCache($mediaFile, $srcPath, $srcPath, $initialSlug);
    }

    /**
     * move the cache files, for the specified mediaFile entity, from source folder to destination folder
     * @param string $mediaFile -> mediaFile entity
     * @param string $srcPath -> path from where to move the file
     * @param string $dstPath -> path where to move the file
     * @param string $initialSlug -> initial slug of the media file
     */
    public static function moveMediaFileCache($mediaFile, $srcPath, $dstPath, $initialSlug)
    {
        // move cache file for images, if any, using the * wildcard
        if (self::getFileType($mediaFile->extension) === 'image') {
            $cacheFileBaseName = $initialSlug . '-' . $mediaFile->id;
            // use the File class, to be able to use wildcard when moving, since storage::disk doesn't support it
            $fileList = File::glob(storage_path('app/' . $srcPath . 'cache/' . $cacheFileBaseName . '*.' . $mediaFile->extension));
            if ($fileList && count($fileList) > 0) {
                // create destination folders if they don't exist
                $folderPath = storage_path('app/' . $dstPath);
                if (!File::isDirectory($folderPath)) {
                    File::makeDirectory($folderPath, 0777, true, true);
                }
                $folderPath .= 'cache/';
                if (!File::isDirectory($folderPath)) {
                    File::makeDirectory($folderPath, 0777, true, true);
                }
                foreach ($fileList as $file) {
                    // replace srcPath with destPath
                    // for example, path:  \erp\storage\app/public/mediafiles/front-6/cache/moon-14-100x100.jpg"
                    // becomes: \erp\storage\app/public/mediafiles/backend-7/cache/moon2-14-100x100.jpg"
                    $newFile = str_replace('/' . $srcPath, '/' . $dstPath, $file);
                    // if mediafile slug changed, replace initialSlug with new slug
                    if ($mediaFile->slug !== $initialSlug) {
                        $newFile = str_replace('/' . $initialSlug . '-' . $mediaFile->id . '-', '/' . $mediaFile->slug . '-' . $mediaFile->id . '-', $newFile);
                    }

                    File::move($file, $newFile);
                }
            }
        }
    }

    /**
     * return the url to the user avatar, with a specified size
     *
     * @param object userInfo : user information; must contain: { user_id, avatar, updated_at }
     * @param string size : size of the desired cropped image
     * @param string route : a route for the controller which resize the image to the specified size
     *
     * @return string
     *          if user avatar is empty or the file does not exists, returns a default avatar
     *          if file with specified size exists, return its url
     *          if file with specified size doesn't exists, returns the provided route
     */
    public static function getUserAvatarUrl($userInfo, $size, $route)
    {
        $defaultAvatar = asset('media/theme/avatars/blank.png');
        if (!$userInfo) {
            return $defaultAvatar;
        }
        $hasAvatar = false;
        $userId = $userInfo->user_id;
        $basePath = 'public/account/user_' . $userId . '/';
        $avatarPath = $basePath . $userInfo->avatar;
        if ($userInfo && $userInfo->avatar && Storage::disk('local')->exists($avatarPath)) {
            $fileExtension = pathinfo($userInfo->avatar, PATHINFO_EXTENSION);
            $fileName = pathinfo($userInfo->avatar, PATHINFO_FILENAME);
            $avatarResizedPath = $fileName . '-' . $userId . '-' . $size . '.' . $fileExtension;
            // if cached file exists
            if (Storage::disk('local')->exists($basePath . $avatarResizedPath)) {
                return Storage::disk('local')->url($basePath . $avatarResizedPath);
            }
            // return a route to the controller which crops the image
            $timeStampQuery = '?t=' . strtotime($userInfo->updated_at);
            return route($route, ['info' => $avatarResizedPath . $timeStampQuery]);
        }
        return $defaultAvatar;
    }

    /**
     * return the url to the media file, for a specified size
     *
     * @param {object} mediaFile : file information; must contain: { id, file, path, extension, updated_at }
     * @param string size : size of the desired cropped image; is of format {width}x{height} , where both width and height are optional
     * @param string route : a route for the controller which resize the image to the specified size
     *
     * @return string
     *          if file is empty or the file does not exists, returns null
     *          if file with specified size exists, return its url
     *          if file with specified size doesn't exists, returns the provided route
     */
    public static function getMediaImageUrl($mediaFile, $size, $route)
    {
        $basePath = 'public/mediafiles/' . $mediaFile->path . '/';
        $mediaFilePath = $basePath . $mediaFile->file;
        if ($mediaFile->file && Storage::disk('local')->exists($mediaFilePath)) {
            $fileResizedPath = $mediaFile->slug . '-' . $mediaFile->id . '-' . $size . '.' . $mediaFile->extension;
            // if cached file exists
            $cacheFilePath = $basePath . 'cache/' . $fileResizedPath;
            if (Storage::disk('local')->exists($cacheFilePath)) {
                return Storage::disk('local')->url($cacheFilePath);
            }
            // return a route to the controller which crops the image
            $timeStampQuery = '?t=' . strtotime($mediaFile->updated_at);
            return route($route, ['info' => $fileResizedPath . $timeStampQuery]);
        }
        return null;
    }

    /**
     * return the url to the media file
     *
     * @param {object} mediaFile : file information; must contain: { id, file, path, extension, updated_at }
     *
     * @return string
     *          if file is empty or the file does not exists, returns null
     *          if file exists, return its url
     */
    public static function getMediaFilePublicUrl($mediaFile, $size = null, $route = null)
    {
        $basePath = 'public/mediafiles/' . $mediaFile->path . '/';
        $mediaFilePath = $basePath . $mediaFile->file;
        if ($mediaFile->file && Storage::disk('local')->exists($mediaFilePath)) {
            if ($mediaFile->type === 'image') {
                $fileResizedPath = $mediaFile->slug . '-' . $mediaFile->id . '-' . $size . '.' . $mediaFile->extension;
                $url = route($route, ['info' => $fileResizedPath]);
                $urlInfo = parse_url($url);
                return $urlInfo['path']; // get only the relative path
            } else {
                return Storage::disk('local')->url($mediaFilePath);
            }
        }
        return null;
    }

    /**
     * return the direct url to the media file
     *
     * @param {object} mediaFile : file information; must contain: { id, file, path, extension, updated_at }
     *
     * @return string
     *          if file is empty or the file does not exists, returns null
     *          if file exists, return its url
     */
    public static function getMediaFileDirectUrl($mediaFile, $size = null, $route = null)
    {
        $basePath = 'public/mediafiles/' . $mediaFile->path . '/';
        $mediaFilePath = $basePath . $mediaFile->file;
        if ($mediaFile->file && Storage::disk('local')->exists($mediaFilePath)) {
            return Storage::disk('local')->url($mediaFilePath);
        }
        return null;
    }

    /**
     * format the size in bytes into human readable size
     * @param {number} size : size to format
     * @param {string} unit : if specified, it will use this unit to format the size
     */
    public static function humanFileSize($size, $unit = "")
    {
        if ((!$unit && $size >= 1 << 30) || $unit == "GB") {
            return number_format($size / (1 << 30), 2) . " GB";
        }

        if ((!$unit && $size >= 1 << 20) || $unit == "MB") {
            return number_format($size / (1 << 20), 2) . " MB";
        }

        if ((!$unit && $size >= 1 << 10) || $unit == "KB") {
            return number_format($size / (1 << 10), 2) . " KB";
        }

        return number_format($size) . " bytes";
    }

    /**
     * combine name and extension to create a file name
     * @param {string} name : file name without extension
     * @param {string} extension : file extension
     */
    public static function getFileName($name, $extension)
    {
        $fileName = $name;
        if ($extension) {
            $fileName .= '.' . $extension;
        }
        return $fileName;
    }

    /**
     * download a file from mediafiles db
     * @param {object} item : media file entry from db; must contain { path, file, slug, extension}
     *
     */
    public static function downloadMediaFile($item)
    {
        if (!$item->file) {
            return;
        }
        $basePath = 'public/mediafiles/' . $item->path . '/';
        $mediaFilePath = $basePath . $item->file;
        if (Storage::disk('local')->exists($mediaFilePath)) {
            return Storage::disk('local')->download($mediaFilePath, self::getFileName($item->slug, $item->extension));
        }
    }

    /**
     * download a file from bills
     * @param {object} item : media file entry from db; must contain { path, file, slug, extension}
     *
     */
    public static function downloadBillFile($item)
    {
        if (!$item->pdf_file) {
            return;
        }
        $billDate = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $item->bill_date);
        $billDateFormatted = $billDate->format('Y-m-d');
        $billYear = $billDate->format('Y');
        $basePath = 'public/bills/company_' . $item->company_id . '/' . $billYear . '/';
        $billFilePath = $basePath . $item->pdf_file;
        $fileName = __('billing.bill.BillFilePrefix') . '_' . $item->bill_number . '_' . $billDateFormatted . '_' . $item->clientTo->short_name . '.pdf';
        if (Storage::disk('local')->exists($billFilePath)) {
            return Storage::disk('local')->download($billFilePath, $fileName);
        } else {
            return false;
        }
    }

    /**
     * get the file type from extension
     * @param {string} extension : file extension
     *
     */
    public static function getFileType($extension)
    {
        $extension = strtolower($extension);
        switch ($extension) {
            case 'jpg':
            case 'jpeg':
            case 'png':
            case 'tiff':
            case 'gif':
            case 'bmp':
                $fileType = 'image';
                break;
            case 'pdf':
                $fileType = 'pdf';
                break;
            case 'doc':
            case 'docx':$fileType = 'doc';
                break;
            default:$fileType = 'unkown';
                break;
        }
        return $fileType;
    }

    /**
     * get the mime type from extension
     * @param {string} extension : file extension
     *
     */
    public static function getMimeType($extension)
    {
        $extension = strtolower($extension);
        $mimet = array(
            'txt' => 'text/plain',
            'htm' => 'text/html',
            'html' => 'text/html',
            'php' => 'text/html',
            'css' => 'text/css',
            'js' => 'application/javascript',
            'json' => 'application/json',
            'xml' => 'application/xml',
            'swf' => 'application/x-shockwave-flash',
            'flv' => 'video/x-flv',

            // images
            'png' => 'image/png',
            'jpe' => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'jpg' => 'image/jpeg',
            'gif' => 'image/gif',
            'bmp' => 'image/bmp',
            'ico' => 'image/vnd.microsoft.icon',
            'tiff' => 'image/tiff',
            'tif' => 'image/tiff',
            'svg' => 'image/svg+xml',
            'svgz' => 'image/svg+xml',

            // archives
            'zip' => 'application/zip',
            'rar' => 'application/x-rar-compressed',
            'exe' => 'application/x-msdownload',
            'msi' => 'application/x-msdownload',
            'cab' => 'application/vnd.ms-cab-compressed',

            // audio/video
            'mp3' => 'audio/mpeg',
            'qt' => 'video/quicktime',
            'mov' => 'video/quicktime',

            // adobe
            'pdf' => 'application/pdf',
            'psd' => 'image/vnd.adobe.photoshop',
            'ai' => 'application/postscript',
            'eps' => 'application/postscript',
            'ps' => 'application/postscript',

            // ms office
            'doc' => 'application/msword',
            'rtf' => 'application/rtf',
            'xls' => 'application/vnd.ms-excel',
            'ppt' => 'application/vnd.ms-powerpoint',
            'docx' => 'application/msword',
            'xlsx' => 'application/vnd.ms-excel',
            'pptx' => 'application/vnd.ms-powerpoint',

            // open office
            'odt' => 'application/vnd.oasis.opendocument.text',
            'ods' => 'application/vnd.oasis.opendocument.spreadsheet',
        );

        if (isset($mimet[$extension])) {
            return $mimet[$extension];
        } else {
            return 'application/octet-stream';
        }
    }

}
