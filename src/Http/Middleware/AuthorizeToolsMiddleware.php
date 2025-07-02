<?php

namespace Backstage\Tools\Http\Middleware;

use Backstage\Tools\ToolsPlugin;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AuthorizeToolsMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!$request->user()) {
            return abort(403, 'Unauthorized');
        }

        if (!ToolsPlugin::get()->isAccessible()) {
            return abort(403, 'Unauthorized');
        }

        return $next($request);
    }
}
