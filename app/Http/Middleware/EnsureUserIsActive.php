<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Factory;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsActive
{
    public function __construct(private readonly Factory $auth) {}

    public function handle(Request $request, Closure $next): Response
    {
        if ($request->user() !== null && ! $request->user()->is_active) {
            $this->auth->guard('web')->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('login')->with('status', 'Your account is inactive. Please contact an administrator.');
        }

        return $next($request);
    }
}
