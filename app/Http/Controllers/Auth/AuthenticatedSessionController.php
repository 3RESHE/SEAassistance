<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        // Authenticate the user
        $request->authenticate();
        $request->session()->regenerate();
    
        // Check if the user is "New User" and needs to change password
        if ($request->user()->user_status === 'New User') {
            return redirect()->route('change.password'); // Redirect to change password
        }
    
        // Role-based redirection
        if ($request->user()->role === 'admin') {
            return redirect('/_admin');
        } elseif ($request->user()->role === 'evaluator') {
            return redirect('/evaluator_home');
        } elseif ($request->user()->role === 'secretary') {
            return redirect('/secretary_home');
        } elseif ($request->user()->role === 'student') {
            return redirect('/student_home');
        }
    
        return redirect()->intended(route('/'));
    }
    

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
