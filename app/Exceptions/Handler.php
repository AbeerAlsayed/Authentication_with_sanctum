<?php

namespace App\Exceptions;

use App\Traits\ApiResponse;
//use http\Message;
use Throwable;
//use PDOException;
use Illuminate\Support\Arr;
//use App\Traits\ApiResponseTrait;
use Illuminate\Database\QueryException;
//use App\Exceptions\Otp\InvalidCodeException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\Access\AuthorizationException;
//use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Exceptions\ThrottleRequestsException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
//use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class Handler extends ExceptionHandler
{
    use ApiResponse;
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
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }


    public function render($request, Throwable $exception)
    {

        if ($exception instanceof AuthorizationException) {
            $message = "This action is unauthorized";
            return $this->error(message: $message,code: 403);
        }

        if ($exception instanceof NotFoundHttpException) {
            $message = "Route/File Not Found";
            return $this->error(message: $message, code: 404);
        }

        if ($exception instanceof AuthenticationException) {
            $message = "Unauthenticated user";
            return $this->error(message: $message,code: 401);
        }


//        if ($exception instanceof ValidationException) {
//            return $this->error($exception->errors(), 422);
//        }

        if ($exception instanceof ValidationException) {
            $errors = $exception->errors();
            $errorMessage = implode(', ', array_map(fn($error) => implode(', ', $error), $errors));
            return $this->error($errorMessage, 422);
        }

        if ($exception instanceof QueryException) {
            $message = "Query failed ,Integrity constraint violation";
            return $this->error($message, code:500);
        }

        if($exception instanceof ThrottleRequestsException)
        {
            $message = "Too Many Attempts";

            return $this->error($message, code:429);

        }

        return parent::render($request, $exception);
    }
}
