<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TransferController extends Controller
{
    /**
     * UPLOAD IMAGES
     *
     * @param Request                              $request
     * @param \App\Http\Responses\StandardResponse $response
     * @param \App\Services\Storage\ImageStorage   $storage
     * @author MS
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function uploadImages(
        Request $request,
        \App\Http\Responses\StandardResponse $response,
        \App\Services\Storage\ImageStorage $storage
    ) {
        $temp_path = $request->file->move(
            storage_path('temp'),
            $request->file->getClientOriginalName()
        );
        $image_path = $storage->setIsGuest(\Auth::guest())
            ->addImageFromImage($temp_path);

        $response->success(true)
            ->setMessage('Image successfully uploaded.')
            ->setDetails(json_encode(['image_path' => $image_path]))
            ->engage();
    }
}
