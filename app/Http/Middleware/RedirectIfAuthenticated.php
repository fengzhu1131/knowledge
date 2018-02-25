<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated {
	/**
	 * Handle an incoming request.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \Closure  $next
	 * @param  string|null  $guard
	 * @return mixed
	 */
	public function handle($request, Closure $next, $guard = null) {
		//验证过的用户直接跳转到提交参数的值中
		/*if (Auth::guard($guard) -> check()) {
			return redirect(empty($request['selSystem']) ? '/' : $request['selSystem']);
		}*/
		return $next($request);
	}

}
