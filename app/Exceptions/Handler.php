<?php
 
namespace App\Exceptions;
 
use Exception;
use App\Error;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Auth;

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
 
    
    public function report(Exception $exception)
    {
        // $userId = 0;
        // if (Auth::user()) {
        //     $userId = Auth::user()->id;
        // }
    
        // $data = array(
        //     'userId' => $userId,
        //     'code' => $exception->getCode(),
        //     'file' => $exception->getFile(),
        //     'line' => $exception->getLine(),
        //     'message' => $exception->getMessage(),
        //     'trace' => $exception->getTraceAsString(),
        // );
    
        // Error::create($data);
        
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
        if ($exception instanceof \Illuminate\Session\TokenMismatchException) {
            return redirect()->route('login');
        }
        
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