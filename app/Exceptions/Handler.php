<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Spatie\MediaLibrary\Exceptions\FileCannotBeAdded\FileUnacceptableForCollection;
use Spatie\MediaLibrary\Exceptions\MediaCannotBeDeleted;
class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Exception  $exception
     * @return void
     */
    public function report(Exception $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $exception
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $exception)
    {



        if ($exception instanceof TokenInvalidException) {
            return response()->json(["success" => false, "message" => 'Token is Invalid'], 400);
        }
        if ($exception instanceof TokenExpiredException) {
            return response()->json(["success" => false, "message" => 'Token is Expired'], 400);
        }
        if ($exception instanceof JWTException) {
            return response()->json(["success" => false, "message" => 'There is Problem with the token'], 400);
        }
        if ($exception instanceof MediaCannotBeDeleted) {
            return response()->json(["success" => false, "message" => 'Image not available to delete'], 400);
        }
        return parent::render($request, $exception);
    }


    protected function unauthenticated($request, AuthenticationException $exception)
    {
        $guard = array_get($exception->guards(),0);
        //dd($guard);

        switch ($guard) {
            case 'api':
                return response()->json(["success" => false, "message" => 'Unauthenticated'], 400);
                break;
            
            default:
                 $redirect = route('login');
                break;
        }
        return $request->expectsJson()
                    ? response()->json(['message' => $exception->getMessage()], 401)
                    : redirect()->guest($redirect);
    }

}
