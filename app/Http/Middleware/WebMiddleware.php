<?php

namespace App\Http\Middleware;

use Closure;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Http\Middleware\BaseMiddleware;

class WebMiddleware extends BaseMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next, ...$roles)
    {
        try {
            $token = Cookie::get('admin_cookie') ? Cookie::get('admin_cookie') : Cookie::get('pasien_cookie');
            $curToken = Cookie::get('admin_cookie') ? 'admin_cookie' : 'pasien_cookie';
            $user = JWTAuth::setToken($token)->toUser();
            // dd($user);

            if ($user == null) {
                if (Cookie::get('admin_cookie') != null) {
                    Cookie::forget('admin_cookie');
                } else {
                    Cookie::forget('pasien_cookie');
                }
                return redirect()->route('login')->with('error', 'Anda telah logout, Silahkan login terlebih dahulu')->withCookie(Cookie::forget($curToken));
            }
        } catch (Exception $e) {
            if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenInvalidException) {
                return redirect()->route('login')->with('error', 'Anda telah logout, Silahkan login terlebih dahulu')->withCookie(Cookie::forget($curToken));
            } else if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenExpiredException) {
                return redirect()->route('login')->with('error', 'Token Kedaluwarsa, Silahkan login ulang')->withCookie(Cookie::forget($curToken));
            } else {
                return redirect()->route('login')->with('error', 'Silahkan login terlebih dahulu')->withCookie(Cookie::forget($curToken));
            }
        }


        if (isset($user) && in_array($user->role, $roles)) {

            return $next($request);
        } else {

            return redirect()->route('login')->with('error', 'Anda tidak memiliki akses');
        }
        return redirect()->route('login')->with('error', 'Anda tidak memiliki akses');
    }
}
