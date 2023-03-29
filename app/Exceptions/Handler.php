<?php

namespace App\Exceptions;

use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Exceptions\ThrottleRequestsException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Exception\RouteNotFoundException;
use Throwable;

class Handler extends ExceptionHandler
{
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
        $this->renderable(function (Throwable $exception, $request) {

            
            if($exception instanceof AuthenticationException)
            {
                return error("Can't access this page without Login!!!",type:'unauthenticated');
            }
           else if($exception instanceof RouteNotFoundException)
            {
                return error("Can't access this page without Login!!!",type:'unauthenticated');
            }

            else if($exception instanceof NotFoundHttpException)
            {
                return error(type:'notfound');
            }

            else if ($exception instanceof ThrottleRequestsException) {
                return error('Too Many Attempts');
            }
            // else 
            //     return $request;
        });
    }
}
