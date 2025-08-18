<?php
 
namespace App\Http\Middleware;
 
use Closure;
 
class BasicAuthMiddleware
{
    public function handle($request, Closure $next)
    {
        $username = env('BASIC_AUTH_USER');
        $password = env('BASIC_AUTH_PASSWORD');
        if(env("IS_WEBSITE_BLOCK") == "1"){
            if ($request->getUser() !== $username || $request->getPassword() !== $password) {
                return response('Unauthorized.', 401, ['WWW-Authenticate' => 'Basic']);
            }
        }
 
        return $next($request);
    }
}