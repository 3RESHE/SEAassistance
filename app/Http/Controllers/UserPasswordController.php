<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserPasswordController extends Controller
{

     public function showChangePasswordForm()
     {
         return view('auth.change-password'); 
     }
 
     // Handle the password change request
     public function changePassword(Request $request)
     {
         // Validate new password
         $request->validate([
             'password' => 'required|min:8|confirmed',
         ]);

         $user = Auth::user();
         $user->password = Hash::make($request->password);
         $user->user_status = 'Active';
         $user->save();
 
         return redirect()->route('dashboard')->with('success', 'Password changed successfully.');
     }
     
}
