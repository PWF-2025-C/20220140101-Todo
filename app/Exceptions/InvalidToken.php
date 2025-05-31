<?php

namespace App\Exceptions;

use Throwable;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

class InvalidToken extends ExceptionHandler
{
    public function render($request, Throwable $exception)
    {
        // Jika ada AuthenticationException, pastikan selalu JSON
        if ($exception instanceof \Illuminate\Auth\AuthenticationException) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthenticated or invalid token',
            ], \Illuminate\Http\Response::HTTP_UNAUTHORIZED);
        }

        if ($exception instanceof TokenExpiredException) {
            return response()->json([
                'success' => false,
                'message' => 'Token has expired',
            ], \Illuminate\Http\Response::HTTP_UNAUTHORIZED);
        }

        if ($exception instanceof TokenInvalidException) {
            return response()->json([
                'success' => false,
                'message' => 'Token is invalid',
            ], \Illuminate\Http\Response::HTTP_UNAUTHORIZED);
        }

        if ($exception instanceof JWTException) {
            return response()->json([
                'success' => false,
                'message' => 'Token is not provided',
            ], \Illuminate\Http\Response::HTTP_UNAUTHORIZED);
        }

        return parent::render($request, $exception);
    }
}

