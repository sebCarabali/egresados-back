<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;

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

        if ($request->ajax()) {

            if ($exception instanceof ModelNotFoundException) {
                $this->response('No records found for: :model',  404, [], ["model" => str_replace("App\\", "", $exception->getModel())]);
            } else if ($exception instanceof MethodNotAllowedHttpException) {
                $this->response("The route does not exist!", 404);
            } else if ($exception instanceof QueryException) {
                $this->response("An error occurred while consulting the database.");
            } else if ($exception instanceof ValidationException) {
                $this->response("The given data was invalid.", 422, $exception->errors());
            }
        }
        return parent::render($request, $exception);
    }

    protected function response($message = "An error has occurred on the server.", $code = 500, $errors = [], $parameters = [])
    {
        throw new HttpResponseException(response()->json([
            "status" => "failure",
            "status_code" => 500,
            "message" => __($message, $parameters),
            "errors" => $errors
        ], $code));
    }
}
