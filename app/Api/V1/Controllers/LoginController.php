<?php

namespace App\Api\V1\Controllers;

use App\Services\Storage\ImageArchiver;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Tymon\JWTAuth\JWTAuth;
use App\Http\Controllers\Controller;
use App\Api\V1\Requests\LoginRequest;
use Tymon\JWTAuth\Exceptions\JWTException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class LoginController extends Controller
{
    /**
     * LOGIN
     * ---
     * @param LoginRequest $request
     * @param JWTAuth $JWTAuth
     * @param ImageArchiver $archive
     * @author MS
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(
        LoginRequest $request,
        JWTAuth $JWTAuth,
        ImageArchiver $archive
    ) {
        $credentials = $request->only(['email', 'password']);

        try {
            $token = $JWTAuth->attempt($credentials);

            if(!$token) {
                throw new AccessDeniedHttpException();
            }
        } catch (JWTException $e) {
            throw new HttpException(500);
        }

        $archive->overrideUser($JWTAuth->toUser($token));
        if ($archive->userHasGuestContent()) {
            $archive->transferGuestContentToUser();
        }
        return response()->json([
            'status' => 'ok',
            'token' => $token,
            'modifier' => session()->getId(),
        ]);
    }
}
