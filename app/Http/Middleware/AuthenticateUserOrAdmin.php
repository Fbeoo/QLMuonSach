<?php

namespace App\Http\Middleware;

use App\Repositories\CategoryRepositoryInterface;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AuthenticateUserOrAdmin
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
            $request->session()->regenerate();
            $request->session()->put('user',Auth::user());
            return redirect()->route('home');
        }
        else if (Auth::guard('admin')->attempt($credentials)) {
            $request->session()->regenerate();
            $request->session()->put('admin',Auth::admin());
            return redirect()->route('dashboard');
        }
        return back()->withErrors([
            'mail' => 'Email or Password was incorrect.',
        ])->onlyInput('mail');
    }
}
