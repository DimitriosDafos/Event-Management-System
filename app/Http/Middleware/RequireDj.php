<?php
namespace App\Http\Middleware;
use Closure;
use Illuminate\Http\Request;

class RequireDj
{
    public function handle(Request $request, Closure $next)
    {
        if (!auth()->check() || !auth()->user()->isDj()) {
            abort(403);
        }
        return $next($request);
    }
}
