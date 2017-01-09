<?php

namespace App\Services\Storage;

use Illuminate\Support\Facades\Storage;


class ImageStorage
{
    private $path_adjustment;

    /**
     * SET IS GUEST
     * ---
     * @param bool $isGuest
     * @author MS
     * @return ImageStorage
     */
    public function setIsGuest($isGuest)
    {
        $this->path_adjustment = ($isGuest) ? 'guest' : 'user';
        $this->path_adjustment .= '/temporary';

        return $this;
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
            $this->path_adjustment . '/' . $filename,
            file_get_contents($image_path)
        );
        unlink($image_path);

        return Storage::disk('public')
            ->url($this->path_adjustment . '/' . $filename);
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
