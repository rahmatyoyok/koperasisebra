<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [
        \Illuminate\Auth\AuthenticationException::class,
        \Illuminate\Auth\Access\AuthorizationException::class,
        \Symfony\Component\HttpKernel\Exception\HttpException::class,
        \Illuminate\Database\Eloquent\ModelNotFoundException::class,
        \Illuminate\Session\TokenMismatchException::class,
        \Illuminate\Validation\ValidationException::class,
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
        if(!config('app.debug') && auth()->check() && !$exception instanceof \Illuminate\Validation\ValidationException) {
            view()->share([
                'exception' => $exception,
                'menus' => \App\Http\Models\Pengaturan\Menu::getMenus(auth()->user()->level_id),
                'user' => auth()->user(),
                'title' => "Error"
            ]);
            if($exception instanceof \BadMethodCallException || $exception instanceof \ErrorException)
                return response()->view('errors-app.else', [], 503);

            $statusCode = method_exists($exception, 'getStatusCode') ? $exception->getStatusCode() : -1;

            if($statusCode >= 500)
                return response()->view('errors-app.5xx');

            if($statusCode >= 400)
                return response()->view('errors-app.4xx');
        }

        /*if($exception instanceof \BadMethodCallException)
            abort(403);*/

        return parent::render($request, $exception);
    }

    /**
     * Convert an authentication exception into an unauthenticated response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Auth\AuthenticationException  $exception
     * @return \Illuminate\Http\Response
     */
    protected function unauthenticated($request, AuthenticationException $exception)
    {
        if ($request->expectsJson()) {
            return response()->json(['error' => 'Unauthenticated.'], 401);
        }

        return redirect()->guest(route('login'));
    }
}
