<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * AdminMiddleware
 * 
 * Middleware that restricts access to admin-only routes in the medical ecommerce system.
 * This middleware ensures that only authenticated users with admin privileges
 * can access protected admin functionality.
 * 
 * Security Features:
 * - Authentication check (user must be logged in)
 * - Authorization check (user must have admin privileges)
 * - Returns 403 Forbidden for unauthorized access
 * 
 * @package App\Http\Middleware
 */
class AdminMiddleware
{
    /**
     * Handle an incoming request and check for admin privileges.
     * 
     * This method performs two security checks:
     * 1. Verifies the user is authenticated (logged in)
     * 2. Verifies the user has admin privileges (is_admin = true)
     * 
     * If either check fails, it returns a 403 Forbidden response.
     * If both checks pass, it allows the request to continue.
     *
     * @param Request $request The incoming HTTP request
     * @param Closure $next The next middleware in the chain
     * @return Response Either a 403 Forbidden response or the result of the next middleware
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if the user is authenticated and has admin privileges
        if (!auth()->check() || !auth()->user()->is_admin) {
            // Return 403 Forbidden if user is not authenticated or not an admin
            abort(403, 'Unauthorized');
        }

        // User is authenticated and is an admin - allow request to continue
        return $next($request);
    }
}
