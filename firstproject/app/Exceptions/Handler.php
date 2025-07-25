<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
 use Illuminate\Database\Eloquent\ModelNotFoundException;

use Throwable;

class Handler extends ExceptionHandler

{

 
public function render($request, Throwable $exception)
{
    if ($exception instanceof ModelNotFoundException) {
        $model = class_basename($exception->getModel());

        return response()->json([
            'success' => false,
            'message' => "{$model} not found"
        ], 404);
    }

        if ($request->expectsJson()) {
        return response()->json([
            'success' => false,
            'message' => 'Internal Server Error',
            'error' => $exception->getMessage()
        ], 500);
    }

    return parent::render($request, $exception);
}







    /**
     * A list of exception types with their corresponding custom log levels.
     *
     * @var array<class-string<\Throwable>, \Psr\Log\LogLevel::*>
     */
    protected $levels = [
        //
    ];

    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<\Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }
}
