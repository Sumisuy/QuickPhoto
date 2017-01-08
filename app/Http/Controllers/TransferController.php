<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Responses\StandardResponse;

class TransferController extends Controller
{
    /**
     * UPLOAD IMAGES
     *
     * @param Request          $request
     * @param StandardResponse $response
     * @author MS
     * @return StandardResponse
     */
    public function uploadImages(Request $request, StandardResponse $response)
    {
        $request->file->move(
            base_path('storage'),
            $request->file->getClientOriginalName()
        );
        $response->success(true)
            ->setMessage('')
            ->setDetails('')
            ->engage();
    }
}
