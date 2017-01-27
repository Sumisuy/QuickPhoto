<?php

namespace App\Api\V1\Controllers;

use App\Services\Storage\ImageArchiver;
use Config;
use App\User;
use Tymon\JWTAuth\JWTAuth;
use App\Http\Controllers\Controller;
use App\Api\V1\Requests\LoginRequest;
use Symfony\Component\HttpKernel\Exception\HttpException;

class SignUpController extends Controller
{
    /**
     * SIGN UP
     * ---
     * @param LoginRequest $request
     * @param JWTAuth $JWTAuth
     * @param ImageArchiver $archive
     * @author MS
     * @return \Illuminate\Http\JsonResponse
     */
    public function signUp(
        LoginRequest $request,
        JWTAuth $JWTAuth,
        ImageArchiver $archive
    ) {
        if (!isset($request->name)) {
            $request->name = 'New User';
        }
        $user = new User($request->all());

        if(!$user->save()) {
            throw new HttpException(500);
        }
        if(!Config::get('boilerplate.sign_up.release_token')) {
            return response()->json(['status' => 'ok'], 201);
        }
        $token = $JWTAuth->fromUser($user);

        $archive->overrideUser($user);
        if ($archive->userHasGuestContent()) {
            $archive->transferGuestContentToUser();
        }
        return response()->json([
            'status' => 'ok',
            'token' => $token,
            'modifier' => session()->getId(),
        ], 201);
    }
}
