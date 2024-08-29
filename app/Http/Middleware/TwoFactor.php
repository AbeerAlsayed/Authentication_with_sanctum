<?php

namespace App\Http\Middleware;

use App\Traits\ApiResponse;
use Closure;

class TwoFactor
{
    use ApiResponse;
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $user = auth()->user();

        if(auth()->check() && $user->two_factor_code)
        {
            if($user->two_factor_expires_at<now()) //expired
            {
                $user->resetTwoFactorCode();
                auth()->logout();
                return $this->success([],'The two factor code has expired. Please login again.',200);

            }
            return $this->error('The two factor code not right. Please login again.',200);

        }

        return $next($request);
    }
}
