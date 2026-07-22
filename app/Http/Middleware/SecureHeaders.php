<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SecureHeaders
{
    /**
     * Handle an incoming request and append secure HTTP headers.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Enforce secure rendering and transport standards
        $response->headers->set('X-Frame-Options', 'SAMEORIGIN'); // Clickjacking prevention
        $response->headers->set('X-Content-Type-Options', 'nosniff'); // MIME-sniffing prevention
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');
        $response->headers->set('X-XSS-Protection', '1; mode=block');
        $response->headers->set('Permissions-Policy', 'geolocation=(), camera=(), microphone=()');

        // Apply a strict Content Security Policy in production (relax in dev to allow Vite HMR WebSocket connections)
        if (config('app.env') === 'production') {
            $response->headers->set('Content-Security-Policy', "default-src 'self'; script-src 'self' 'unsafe-inline' 'unsafe-eval' https://api.qrserver.com https://unpkg.com; style-src 'self' 'unsafe-inline' https://fonts.googleapis.com https://unpkg.com; font-src 'self' https://fonts.gstatic.com; img-src 'self' data: https://images.unsplash.com https://api.qrserver.com https://unpkg.com https://*.tile.openstreetmap.org; connect-src 'self' https://nominatim.openstreetmap.org; frame-ancestors 'none';");
        }

        return $response;
    }
}
