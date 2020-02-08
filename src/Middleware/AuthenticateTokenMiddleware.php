<?php

namespace Jaguar\Core\Security\Middleware;

use Closure;
use Tymon\JWTAuth\Http\Middleware\BaseMiddleware;
//use Illuminate\Auth\Middleware\Authenticate as Middleware;

use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;

class AuthenticateTokenMiddleware extends BaseMiddleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string
     */
    /*
    protected function redirectTo($request)
    {
        if (! $request->expectsJson()) {
            return route('unauthenticated');
        }
    }
    */

    public function handle($request, Closure $next)
    {   
        //$this->authenticate($request);
        //dd('AuthenticateToken');
        //dd($this->auth);
        $this->checkForToken($request);        

        try {
            //dd($this->auth->parseToken()->getPayload()->toArray());//->authenticate();
            /*
            if (! $this->auth->parseToken()->authenticate()) {
                dd('User not found');
                throw new UnauthorizedHttpException('jwt-auth', 'User not found');
            }
            */
            $token_str = $this->auth->getToken()->get();            
            $tokenArray = $this->auth->parseToken()->getPayload()->toArray();
            $request->merge(['tokenArray' => $tokenArray]);
            
        } catch (TokenInvalidException $e) {
            throw new UnauthorizedHttpException('jwt-auth', $e->getMessage(), $e, $e->getCode());
        } catch (TokenExpiredException $e) {
            throw new UnauthorizedHttpException('jwt-auth', $e->getMessage(), $e, $e->getCode());
        }

        return $next($request);
    }
}
