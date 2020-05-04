<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Exceptions\ThrottleRequestsException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

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
     * @param \Exception $exception
     * @return void
     * @throws Exception
     */
    public function report(Exception $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Exception $exception
     * @return \Illuminate\Http\JsonResponse
     */
    public function render($request, Exception $exception)
    {
        if ($exception instanceof \Illuminate\Validation\ValidationException)
            return response()->json(['status' => false, 'message' => 'Form Hatası', 'errors' => $exception->validator->getMessageBag()], 422); //type your error code.

        if ($exception instanceof ThrottleRequestsException)
            return response()->json(['status' => false, 'message' => 'Çok fazla sayıda istek gönderdiniz lütfen daha sonra tekrar deneyin'], 429);

        if ($exception instanceof UnauthorizedHttpException)
        {
            $preException = $exception->getPrevious();
            if ($preException instanceof \Tymon\JWTAuth\Exceptions\TokenExpiredException)
                return response()->json(['status' => false, 'message' => 'Oturum süresi dolmuş'], 401);
            else if ($preException instanceof \Tymon\JWTAuth\Exceptions\TokenInvalidException)
                return response()->json(['status' => false, 'message' => 'Oturum geçersiz'], 401);
            else if ($preException instanceof \Tymon\JWTAuth\Exceptions\TokenBlacklistedException)
                return response()->json(['status' => false, 'message' => 'Oturum karalisteye alındı'], 401);

            if ($exception->getMessage() === 'Token not provided')
                return response()->json(['status' => false, 'message' => 'Oturum bulunamadı'], 401);
        }
        return parent::render($request, $exception);
    }
}
