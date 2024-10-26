<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, $role)
    {
        if (!Auth::check()) {
            return redirect('/');
        }

        if (Auth::user()->role != $role) {
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
        

        // Put this to department_role middleware


        // if (Auth::user()->department_role != $department_role) {
        //     switch (Auth::user()->department_role) {
        //         case 'evac_coi':
        //             return redirect('/coi_evaluation');
        //         case 'evac_cet':
        //             return redirect('/cet_evaluation');
        //         case 'evac_ced':
        //             return redirect('/cod_evaluation');
        //         case 'evac_cnhs':
        //             return redirect('/cnhs_evaluation');
        //             Secretary
        //         case 'sec_coi':
        //             return redirect('/coi_secretary');
        //         case 'sec_cet':
        //             return redirect('/cet_secretary');
        //         case 'sec_ced':
        //             return redirect('/cod_secretary');
        //         case 'secc_cnhs':
        //             return redirect('/cnhs_secretary');
                    
        //         default:
        //             return redirect('/');
        //     }
        // }




        return $next($request);
    }
}
