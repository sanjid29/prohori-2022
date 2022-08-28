<?php

namespace App\Mainframe\Http\Middleware;

use App\Mainframe\Features\Core\Traits\SendResponse;
use Auth;
use Closure;

class InjectTenant
{
    use SendResponse;

    /**
     * Check if the request contains a valid X-Auth-Token and client-id
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (Auth::check()) {
            /** @var \App\User $user */
            $user = Auth::user();
            if ($user->ofTenant()) {
                $tenantId = $user->tenant_id;

                if (!$request->has('tenant_id')) {
                    request()->merge(['tenant_id' => $tenantId]);
                }
            }
        }

        return $next($request);
    }

}