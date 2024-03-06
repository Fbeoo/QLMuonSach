<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AuthUser
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $credentials = $request->only('mail','password');
        if (Auth::guard('web')->attempt($credentials)) {
            if (Auth::user()->status === User::statusInactive) {
                return redirect()->route('noticeVerifyEmail')->with('user',Auth::user());
            }
            else if (Auth::user()->status === User::statusLock) {
                return back()->with('notificationLock',@trans('message.accountLocked'));
            }
            $request->session()->regenerate();
            $request->session()->put('user',Auth::user());
            return redirect()->route('home');
        }
        else {
            return back()->withErrors([
                'mail' => 'Email or Password was incorrect.',
            ])->onlyInput('mail');
        }
    }
}
