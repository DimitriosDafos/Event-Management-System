<?php
namespace App\Http\Middleware;
use Closure; use Illuminate\Http\Request;
class RequireMarketing {
    public function handle(Request $request, Closure $next) {
        if (!auth()->check() || !auth()->user()->isMarketing()) abort(403);
        return $next($request);
    }
}
