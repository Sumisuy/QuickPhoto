<?php

namespace App\Api\V1\Controllers;

use Config;
use App\User;
use Tymon\JWTAuth\JWTAuth;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Password;
use App\Api\V1\Requests\ResetPasswordRequest;
use Symfony\Component\HttpKernel\Exception\HttpException;

class ResetPasswordController extends Controller
{
    /**
     * RESET PASSWORD
     * ---
     * @param ResetPasswordRequest $request
     * @param JWTAuth $JWTAuth
     * @author MS
     * @return \Illuminate\Http\JsonResponse
     */
    public function resetPassword(
        ResetPasswordRequest $request,
        JWTAuth $JWTAuth
    ) {
        $response = $this->broker()->reset(
            $this->credentials($request), function ($user, $password) {
                $this->reset($user, $password);
            }
        );
        if($response !== Password::PASSWORD_RESET) {
            throw new HttpException(500);
        }
        if(!Config::get('boilerplate.reset_password.release_token')) {
            return response()->json(['status' => 'ok',]);
        }
        $user = User::where('email', '=', $request->get('email'))->first();

        return response()->json([
            'status' => 'ok',
            'token' => $JWTAuth->fromUser($user)
        ]);
    }

    /**
     * BROKER
     * ---
     * @author MS
     * @return \Illuminate\Contracts\Auth\PasswordBroker
     */
    public function broker()
    {
        return Password::broker();
    }

    /**
     * CREDENTIALS
     * ---
     * @param  ResetPasswordRequest  $request
     * @author MS
     * @return array
     */
    protected function credentials(ResetPasswordRequest $request)
    {
        return $request->only(
            'email', 'password', 'password_confirmation', 'token'
        );
    }

    /**
     * RESET
     * ---
     * @param  \Illuminate\Contracts\Auth\CanResetPassword  $user
     * @param  string  $password
     * @author MS
     * @return void
     */
    protected function reset($user, $password)
    {
        $user->password = $password;
        $user->save();
    }
}
