<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Log;

class Authenticate {
	
	/**
	 * The authentication guard factory instance.
	 *
	 * @var \Illuminate\Contracts\Auth\Factory
	 */
	protected $auth;

	/**
	 * Create a new middleware instance.
	 *
	 * @param  \Illuminate\Contracts\Auth\Factory  $auth
	 * @return void
	 */
	public function __construct(Auth $auth) {
		$this -> auth = $auth;
	}

	/**
	 * Handle an incoming request.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \Closure  $next
	 * @param  string|null  $guard
	 * @return mixed
	 */
	public function handle($request, Closure $next, $guard = null) {
		if (Auth::guard($guard) -> guest()) {
			if ($request -> ajax() || $request -> wantsJson()) {
				return response('Unauthorized.', 401);
			} else {
				if(!empty($_SERVER['REQUEST_URI'])&&preg_match("/oper/u", $_SERVER['REQUEST_URI'])){
					return redirect() -> guest('oper/login');
				}
				return redirect() -> guest('login');
			}
		}

		return $next($request);
	}

}
