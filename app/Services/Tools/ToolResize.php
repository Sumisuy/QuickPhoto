<?php

namespace App\Services\Tools;

use App\Services\Storage\ImageArchiver;
use Intervention\Image\Facades\Image;

class ToolResize
{
    /**
     * RESIZE IMAGES
     * ---
     * @param array $images
     * @param ImageArchiver $archive
     * @param null|int $width
     * @param null|int $height
     * @param bool $keep_aspect_ratio
     * @param bool $allow_up_sizing
     * @author MS
     */
    public function resizeImages(
        array $images,
        ImageArchiver $archive,
        $width = null,
        $height = null,
        $keep_aspect_ratio = true,
        $allow_up_sizing = true
    ) {
        foreach ($images as $image) {
            $image_path = $archive->getFullPathFromFilename($image);
            $imagick = Image::make($image_path);

            $imagick->resize(
                $width, $height, function ($constraint)
                    use ($keep_aspect_ratio, $allow_up_sizing) {
                        if ($keep_aspect_ratio) {
                            $constraint->aspectRatio();
                        }
                        if (!$allow_up_sizing) {
                            $constraint->upsize();
                        }

                    }
            );
        }
    }
}
