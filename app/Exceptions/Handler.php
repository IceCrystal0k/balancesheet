<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;

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

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $exception
     * @return \Illuminate\Http\Response
     */
    public function render($request, Throwable $exception)
    {
        if ($this->isHttpException($exception)) {
            if ($exception->getStatusCode() == 404) {
                $extension = pathinfo($request->fullUrl(), PATHINFO_EXTENSION);
                if ($extension != 'jpg' && $extension != 'png' && $extension != 'svg') {
                    $authUser = (object) ['avatar' => asset('media/theme/avatars/blank.png')];
                    $data = ['info' => __('general.PageNotFoundInfo'), 'description' => __('general.PageNotFoundDescription')];
                    return response()->view('errors.' . '404', compact('data', 'authUser'), 404);
                } else {
                    http_response_code(404);
                    die();
                }
            }
        } else if (is_a($exception, 'Illuminate\Database\Eloquent\ModelNotFoundException')) {
            $authUser = (object) ['avatar' => asset('media/theme/avatars/blank.png')];
            $data = ['info' => __('general.ModelNotFoundInfo'), 'description' => __('general.ModelNotFoundDescription')];
            return response()->view('errors.' . '404', compact('data', 'authUser'), 404);
        }

        return parent::render($request, $exception);
    }
}