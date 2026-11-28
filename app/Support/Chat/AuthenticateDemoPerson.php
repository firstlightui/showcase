<?php

namespace App\Support\Chat;

use Closure;
use Illuminate\Auth\GenericUser;
use Illuminate\Http\Request;

/** The recipe's bearer token is the person's key: there are no accounts, only the demo people. */
class AuthenticateDemoPerson
{
    public function handle(Request $request, Closure $next)
    {
        $person = (string) $request->bearerToken();
        abort_unless(isset(DemoPeople::PEOPLE[$person]), 401);
        $request->setUserResolver(fn () => new GenericUser(['id' => $person]));

        return $next($request);
    }
}
