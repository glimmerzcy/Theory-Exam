<?php

namespace App\Http\Middleware;

use Closure;
use App\Http\Controllers\Student\LoginController as Login;
use Illuminate\Http\Request;

class Authentication
{

    protected $login;

    public function __construct(Login $login){
        $this->login = $login;
    }

    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @return mixed
     */
    public function handle( $request, Closure $next)
    {
        if ($request->session()->has('data')) {
            return $next($request);
        } else {
            $token = $request->header('token');
            if ($this->login->storage($token)) {
                return $next($request);
            } else {
                return response()->json([
                    'error_code' => 1000,
                    'message' => 'token错误，登录失败',
                    'data' => null
                ]);
            }
        }
    }
}
