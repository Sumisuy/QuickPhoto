<?php

namespace App\Api\V1\Controllers;

use App\Http\Responses\StandardResponse;
use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Password;
use App\Api\V1\Requests\ForgotPasswordRequest;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ForgotPasswordController extends Controller
{
    /**
     * SEND RESET EMAIL
     * ---
     * @param ForgotPasswordRequest $request
     * @param StandardResponse $response
     * @author MS
     * @return \Illuminate\Http\JsonResponse
     */
    public function sendResetEmail(
        ForgotPasswordRequest $request,
        StandardResponse $response
    ) {
        $user = User::where('email', '=', $request->get('email'))->first();

        if(!$user) {
            throw new NotFoundHttpException();
        }
        $broker = $this->getPasswordBroker();
        $sendingResponse = $broker->sendResetLink($request->only('email'));

        if($sendingResponse !== Password::RESET_LINK_SENT) {
            throw new HttpException(500);
        }

        return response()->json(['status' => 'ok'], 200);
    }

    /**
     * GET PASSWORD BROKER
     * ---
     * @author MS
     * @return \Illuminate\Contracts\Auth\PasswordBroker
     */
    private function getPasswordBroker()
    {
        return Password::broker();
    }
}
