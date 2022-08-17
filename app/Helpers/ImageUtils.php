<?php
namespace App\Helpers;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Intervention\Image\ImageManagerStatic as Image;

class ImageUtils
{
    /**
     * crop and rotate an image based on provided path
     * @param object $request -> crop/rotate request { x, y, width, height, rotate, displayWidth, displayHeight }
     * @param object $mediaFile -> media file object
     * @param string $storePath -> path where to store the file
     */
    public static function cropAndRotate($request, $filePath)
    {
        $image = Image::make(Storage::disk('local')->path($filePath));
        if ($request->rotate) {
            $image->rotate(-$request->rotate);
        }
        self::crop($image, $request);
        $image->save();
    }

     /**
     * crop an image
     * @param object $image -> image object to crop
     * @param object $request -> crop request { x, y, width, height, rotate, displayWidth, displayHeight }
     */
    public static function crop($image, $request)
    {
        $imgWidth = $image->width();
        $imgHeight = $image->height();

        // calculate crop size, according to the image info and crop info
        // the image displayed when visual cropping has displayWidth and displayHeight,
        // therefor translate cropping info into imgWidth and imgHeight
        if ($imgWidth > $request->displayWidth) {
            $x = round($request->x * $imgWidth / $request->displayWidth);
            $width = round($request->width * $imgWidth / $request->displayWidth);
        }
        else {
            $x = $request->x;
            $width = $request->width;
        }
        if ($imgHeight > $request->displayHeight) {
            $y = round($request->y * $imgHeight / $request->displayHeight);
            $height = round($request->height * $imgHeight / $request->displayHeight);
        }
        else {
            $y = $request->y;
            $height = $request->height;
        }

        $image->crop($width, $height, $x, $y);
    }
}
