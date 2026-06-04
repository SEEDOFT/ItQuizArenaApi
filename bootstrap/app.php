<?php

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Exceptions\ThrottleRequestsException;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->statefulApi();
        $middleware->api(prepend: [
            EnsureFrontendRequestsAreStateful::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->shouldRenderJsonWhen(
            fn (Request $request) => $request->is('api/*'),
        );

        $exceptions->render(function (AuthenticationException $e, Request $request) {
            return response()->json([
                'status' => ['code' => '401', 'message' => 'Unauthenticated', 'success' => false],
                'data' => [],
            ], 401);
        });

        $exceptions->render(function (AuthorizationException $e, Request $request) {
            return response()->json([
                'status' => ['code' => '403', 'message' => 'Forbidden', 'success' => false],
                'data' => [],
            ], 403);
        });

        $exceptions->render(function (NotFoundHttpException|ModelNotFoundException $e, Request $request) {
            return response()->json([
                'status' => ['code' => '404', 'message' => 'Resource not found', 'success' => false],
                'data' => [],
            ], 404);
        });

        $exceptions->render(function (ValidationException $e, Request $request) {
            return response()->json([
                'status' => ['code' => '422', 'message' => 'Validation failed', 'success' => false],
                'data' => ['errors' => $e->errors()],
            ], 422);
        });

        $exceptions->render(function (ThrottleRequestsException $e, Request $request) {
            return response()->json([
                'status' => ['code' => '429', 'message' => 'Too many requests', 'success' => false],
                'data' => [],
            ], 429);
        });

        $exceptions->render(function (HttpException $e, Request $request) {
            return response()->json([
                'status' => ['code' => (string) $e->getStatusCode(), 'message' => $e->getMessage() ?: 'Server error', 'success' => false],
                'data' => [],
            ], $e->getStatusCode());
        });

        $exceptions->render(function (Throwable $e, Request $request) {
            $code = $e instanceof HttpException ? $e->getStatusCode() : 500;

            return response()->json([
                'status' => ['code' => (string) $code, 'message' => $e->getMessage() ?: 'Server error', 'success' => false],
                'data' => [],
            ], $code);
        });
    })->create();
