<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        if (!Auth::check()){
            return redirect('login');
        }

        if (Auth::user()->role !== $role ){
            
            return match (Auth::user()->role) {
                'admin' => redirect()->route('admin.dashboard'),
                'guru' => redirect()->route('guru.dashboard'),
                'wali_kelas' => redirect()->route('wali_kelas.dashboard'),
                default => abort(403, 'Akses tidak sah !'),
            };
        }

        return $next($request);
    }
}
