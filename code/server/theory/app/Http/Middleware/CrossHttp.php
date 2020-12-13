<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CrossHttp
{
    /**
     * Handle an incoming request.
     *
     * @param  Request  $request
     * @param Closure $next
     * @return mixed
     */

    public function handle($request, Closure $next){
       $response = $next($request);
       $IlluminateResponse = 'Illuminate\Http\Response';
       $SymfonyResponse = 'Symfony\Component\HttpFoundation\Response';
       $origin = $request->server('HTTP_ORIGIN') ? $request->server('HTTP_ORIGIN') : 'localhost:8000';

       $headers = [
           'Access-Control-Allow-Origin'=>$origin,
           'Access-Control-Allow-Headers'=>'Origin, Content-Type, Cookie, X-CSRF-TOKEN, Accept, Authorization, X-XSRF-TOKEN',
           'Access-Control-Expose-Headers'=>'Authorization, authenticated',
           'Access-Control-Allow-Methods'=>'GET, POST, PATCH, PUT, OPTIONS',
           'Access-Control-Allow-Credentials'=>'true'
       ];

//       $response->header('Access-Control-Allow-Origin', $origin);
//       $response->header('Access-Control-Allow-Headers', 'Origin, Content-Type, Cookie, X-CSRF-TOKEN, Accept, Authorization, X-XSRF-TOKEN');
//       $response->header('Access-Control-Expose-Headers', 'Authorization, authenticated');
//       $response->header('Access-Control-Allow-Methods', 'GET, POST, PATCH, PUT, OPTIONS');
//       $response->header('Access-Control-Allow-Credentials', 'true');

        if ($response instanceof $IlluminateResponse) {
            foreach ($headers as $key => $value) {
                $response->header($key, $value);
            }
            return $response;
        }

        if ($response instanceof $SymfonyResponse) {
            foreach ($headers as $key => $value) {
                $response->headers->set($key, $value);
            }
            return $response;
        }


       return $response;
   }


}

