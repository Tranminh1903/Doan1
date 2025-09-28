<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class Admin
{
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->user() || $request->user()->role !== 'admin') {
            return $next($request);
        }
        return redirect('/')->with('error', 'Bạn không có quyền truy cập trang này.');
    }
}
