<?php

use Illuminate\Http\Request;
use Illuminate\Foundation\Application;
use Illuminate\Database\QueryException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\Client\RequestException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Exception\MethodNotAllowedException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        //
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->render(function (AuthenticationException $e, Request $request) {
            return response()->json(['status' => 'error', 'error' => $e->getMessage()], config('constants.HTTP_UNAUTHORIZED'));
        });
        $exceptions->render(function (AuthorizationException $e, Request $request) {
            return response()->json(['status' => 'error', 'error' => $e->getMessage()], config('constants.HTTP_UNAUTHORIZED'));
        });
        $exceptions->render(function (ModelNotFoundException $e, Request $request) {
            return response()->json(['status' => 'error', 'error' => $e->getMessage()], config('constants.HTTP_NOT_FOUND'));
        });
        $exceptions->render(function (NotFoundHttpException $e, Request $request) {
            return response()->json(['status' => 'error', 'error' => 'Specified URL not found'], config('constants.HTTP_NOT_FOUND'));
        });
        $exceptions->render(function (RequestException $e, Request $request) {
            return response()->json(['status' => 'error', 'error' => $e->getMessage()], config('constants.HTTP_NOT_FOUND'));
        });
        $exceptions->render(function (MethodNotAllowedException $e, Request $request) {
            return response()->json(['status' => 'error', 'error' => 'The specified method for the request is invalid'], config('constants.HTTP_METHOD_NOT_ALLOWED'));
        });
        $exceptions->render(function (QueryException $e, Request $request) {
            $errorCode = $e->errorInfo[1];
            $errorMsg = $e->errorInfo[2];
            return response()->json(['status' => 'error', 'error' => $errorMsg], config('constants.HTTP_INTERNAL_SERVER_ERROR'));
        });
        $exceptions->render(function (\Exception $e, Request $request) {
            return response()->json(['status' => 'error', 'error' => $e->getMessage()], config('constants.HTTP_INTERNAL_SERVER_ERROR'));
        });
    })->create();
