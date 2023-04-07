<?php

namespace App\Exceptions;

use App\Models\Error;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Exceptions\ThrottleRequestsException;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
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
        AuthorizationException::class,
        HttpException::class, 
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

            $user_id = 1;

            if (Auth::user()) {
                $user_id = Auth::user()->id;
            }

            $data = array(
                'user_id'   => $user_id,
                'code'      => $exception->getCode(),
                'file'      => $exception->getFile(),
                'line'      => $exception->getLine(),
                'message'   => $exception->getMessage(),
                'trace'     => $exception->getTraceAsString(),
            );

           Error::create($data);
           
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

            else if($exception instanceof AccessDeniedHttpException)
            {
                return error("Access denied!!!",[],'unauthenticated');
            }
            else
            {
                return error("Other Error",$exception->getMessage());
            }

        });
    }
}
