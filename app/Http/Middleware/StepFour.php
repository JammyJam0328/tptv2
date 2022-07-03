<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class StepFour
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if (auth()->user()->step!='4') {
              return redirect()->route('applicant.home');
        }
        return $next($request);
    }
}
