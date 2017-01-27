<?php

namespace App\Http\Controllers;

use App\Http\Responses\StandardResponse;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * CONTROLLER CONSTRUCTOR
     * ---
     * @param Request $request
     * @author MS
     */
    public function __construct(Request $request)
    {
        if ($request->header('modifier')) {
            session()->setId($request->header('modifier'));
        }
    }

    /**
     * UNKNOWN ERROR RESPONSE
     * ---
     * @param \Exception $exception
     * @param StandardResponse $response
     * @author MS
     * @return StandardResponse
     */
    protected function unknownErrorResponse(
        \Exception $exception,
        StandardResponse $response
    ) {
        \Log::error($exception->getMessage());
        $response->setStatus(500);
        $response->setMessage('Error');
        $response->setDetails(
            'message',
            'Unknown error has occurred on the server'
        );
    }
}
