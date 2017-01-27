<?php

namespace App\Api\V1\Controllers;

use App\Api\V1\Requests\ImageArchive\ResizeImagesRequest;
use App\Http\Controllers;
use App\Http\Responses\StandardResponse;
use App\Services\Storage\ImageArchiver;
use App\Services\Tools\ToolResize;

class ImageSizeController extends Controllers\Controller
{
    /**
     * RESIZE IMAGES
     * ---
     * @param ResizeImagesRequest $request
     * @param ImageArchiver $archive
     * @param StandardResponse $response
     * @param ToolResize $image_resize_tool
     * @author MS
     * @return StandardResponse
     */
    public function resizeImages(
        ResizeImagesRequest $request,
        ImageArchiver $archive,
        StandardResponse $response,
        ToolResize $image_resize_tool
    ) {
        $image_resize_tool->resizeImages(
            $request->filenames,
            $archive,
            (isset($request->width)) ? $request->width : null,
            (isset($request->height)) ? $request->height : null,
            (isset($request->aspect)) ? $request->aspect : true,
            (isset($request->upscaling)) ? $request->upscaling : true
        );

        $response->setDetails('message', 'Images have been resized')
            ->selectObject()
            ->engage();
    }
}
