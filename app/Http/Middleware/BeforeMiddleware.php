<?php

namespace App\Http\Middleware;

use Closure;

class BeforeMiddleware {
	public function handle($request, Closure $next) {
		// 执行业务逻辑操作
		return $next($request);
	}

}
