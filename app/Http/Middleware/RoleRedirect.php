<?php 

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleRedirect
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();
        if ($user) {
            // Check for admin panel access.
            if ($request->is('admin*') && (!$user->hasRole('admin') && ! $user->hasRole('super-admin')) ) {
                
                    abort(403, 'You do not have access to Admin Panel.');
                
            }

            // Check for user dashboard access.
            if ($request->is('user*') && ($user->hasRole('admin') || $user->hasRole('super-admin'))) {
                
                    abort(403, 'Admins cannot access User Dashboard.');
                
            }
        }

        return $next($request);
    }
}
