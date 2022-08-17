<?php
namespace App\Http\Controllers\Picture;

use App\Http\Controllers\Controller;
use App\Models\UserInfo;
use App\Models\MediaFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Intervention\Image\ImageManagerStatic as Image;

class PictureRenderController extends Controller
{
    public function renderUserPicture($pictureName)
    {
        $fileInfo = $this->extractFileInfo($pictureName);
        if (!$fileInfo || $fileInfo->id == 0) {
            return $this->getPageNotFound();
        }

        $basePath = 'public/account/user_' . $fileInfo->id . '/';
        $avatarPath = $basePath . $fileInfo->fileName;

        // if cached file exists
        if (Storage::disk('local')->exists($avatarPath)) {
            // $timeModified = Storage::disk('local')->lastModified($avatarPath);
            $this->outputFile($avatarPath);
        }

        $data = UserInfo::where('user_id', $fileInfo->id)->first();
        if ($data == null || $data->avatar == '') {
            return $this->getPageNotFound();
        }

        $fileExists = false;

        $avatarPath = $basePath . $data->avatar;
        $avatarResizedPath = $basePath . $fileInfo->fileName;

        if ($data->avatar && Storage::disk('local')->exists($avatarPath)) {
            $fileExists = true;
        }
        if (!$fileExists) {
            return $this->getPageNotFound();
        }

        $image = Image::make(Storage::disk('local')->path($avatarPath));

        $image->fit($fileInfo->width, $fileInfo->height, function ($constraint) {
            $constraint->upsize();
        });
        $image->save(Storage::disk('local')->path($avatarResizedPath));

        return $image->response();
    }

    public function renderMediaPicture($pictureName)
    {
        $fileInfo = $this->extractFileInfo($pictureName);
        if (!$fileInfo || $fileInfo->id == 0) {
            return $this->getPageNotFound();
        }

        $data = MediaFile::where('id', $fileInfo->id)->first();
        if (!$data || !$data->file) {
            return $this->getPageNotFound();
        }

        $basePath = 'public/mediafiles/' . $data->path . '/';
        $filePath = $basePath . 'cache/'.$fileInfo->fileName;

        // if cached file exists
        if (Storage::disk('local')->exists($filePath)) {
            // $timeModified = Storage::disk('local')->lastModified($filePath);
            $this->outputFile($filePath);
        }


        if ($data == null || $data->file == '') {
            return $this->getPageNotFound();
        }

        // if neither height nor width specified, return the actual media image file
        if (!$fileInfo->width && !$fileInfo->height) {
            $this->outputFile($basePath.$data->file);
        }

        $fileExists = false;

        $filePath = $basePath . $data->file;
        $fileResizedPath = $basePath . 'cache/'. $fileInfo->fileName;

        if ($data->file && Storage::disk('local')->exists($filePath)) {
            $fileExists = true;
        }
        if (!$fileExists) {
            return $this->getPageNotFound();
        }

        $image = Image::make(Storage::disk('local')->path($filePath));

        $folderPath = storage_path('app/'.$basePath . 'cache/');
        if (!File::isDirectory($folderPath)) {
            File::makeDirectory($folderPath, 0777, true, true);
        }

        // if only height specified, calculate the width from the image, since width is mandatory for fit function
        if ($fileInfo->height && !$fileInfo->width) {
            $fileInfo->width = round($image->width() * $fileInfo->height / $image->height());
        }

        $image->fit($fileInfo->width, $fileInfo->height, function ($constraint) {
            $constraint->upsize();
        });
        $image->save(Storage::disk('local')->path($fileResizedPath));

        return $image->response();
    }

    private function extractFileInfo($query)
    {
        $pattern = '/(.+)-(\d+)-(\d*)x(\d*)\.(\w+)\??.*$/';
        if (!preg_match($pattern, $query, $capture)) {
            return null;
        }

        $data = (object) [];
        $startName = $capture[1];
        $data->id = (int) $capture[2];
        $data->width = (int) $capture[3];
        $data->height = (int) $capture[4];
        $data->extension = $capture[5];

        $fileNameWidth = $data->width;
        $fileNameHeight = $data->height;

        if ($data->width == 0) {
            $data->width = null; //'*';
            $fileNameWidth = '';
        }
        if ($data->height == 0) {
            $data->height = null; // '*';
            $fileNameHeight = '';
        }

        $data->fileName = $startName . '-' . $data->id . '-' . $fileNameWidth . 'x' . $fileNameHeight . '.' . $data->extension;

        return $data;
    }

    private function getPageNotFound()
    {
        $data = ['info' => __('general.PageNotFoundInfo'), 'description' => __('general.PageNotFoundDescription')];
        return response()->view('errors.404', compact('data'), 404);
    }

    private function outputFile($filePath)
    {
        $extension = pathinfo($filePath, PATHINFO_EXTENSION);
        $fileContent = Storage::disk('local')->get($filePath);

        switch ($extension) {
            case "gif":$ctype = "image/gif";
                break;
            case "png":$ctype = "image/png";
                break;
            case "jpeg":
            case "jfif":
            case "jpg":$ctype = "image/jpeg";
                break;
            default:
        }

        header('Content-type: ' . $ctype);
        echo $fileContent;
        die();
    }
}
