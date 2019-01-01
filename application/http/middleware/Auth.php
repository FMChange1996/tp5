<?php

namespace app\http\middleware;

class Auth
{
    public function handle($request, \Closure $next)
    {
        if (empty(session('token'))){
            return redirect('/');
        }
        return $next($request);
    }
}
