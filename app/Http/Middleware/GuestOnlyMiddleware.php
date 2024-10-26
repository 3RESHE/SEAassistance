<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class GuestOnlyMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check()) {
            switch (Auth::user()->role) {
                case 'admin':
                    return redirect('/_admin');
                case 'evaluator':
                    return redirect('/evaluator_home');
                case 'secretary':
                    return redirect('/secretary_home');
                case 'student':
                    return redirect('/student_home');
                default:
                    return redirect('/');
            }
        }





        return $next($request);
    }
}
