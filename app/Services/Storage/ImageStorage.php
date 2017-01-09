<?php

namespace App\Services\Storage;

use Illuminate\Support\Facades\Storage;


class ImageStorage
{
    private static $path_adjustment;

    /**
     * SET PATH ADJUSTMENT
     * ---
     * Set path adjustment property.  Images will be stored in the app/public
     * folder, use this method to adjust sub folders for user/session
     * organisation.
     * @param string $path
     * @author MS
     */
    public static function setPathAdjustment($path)
    {
        self::$path_adjustment = $path;
    }

    /**
     * ADD IMAGE FROM IMAGE
     * ---
     * @param string $image_path
     * @author MS
     * @return string
     */
    public function addImageFromImage($image_path)
    {
        $filename = pathinfo($image_path, PATHINFO_BASENAME);

        Storage::disk('public')->put(
            self::$path_adjustment . '/' . $filename,
            file_get_contents($image_path)
        );
        unlink($image_path);

        return Storage::disk('public')
            ->url(self::$path_adjustment . '/' . $filename);
    }

//    public function addImageFromPdf()
//    {
//
//    }
//
//    public function addImageFromZip()
//    {
//
//    }
}
