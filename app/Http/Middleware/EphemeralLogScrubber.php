<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EphemeralLogScrubber
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        return $next($request);
    }

    /**
     * Handle tasks after the response has been sent to the browser.
     */
    public function terminate(Request $request, Response $response): void
    {
        // Check if sensitive PII was part of the request
        if ($request->hasAny(['minor_name', 'phone_number', 'family_details'])) {
            // MVP: Aggressively scrub the default laravel log to prevent PII leakage
            $logPath = storage_path('logs/laravel.log');
            if (file_exists($logPath)) {
                // In production, a more sophisticated scrubbing algorithm would be used.
                // For MVP, we truncate or overwrite to guarantee ephemeral nature.
                file_put_contents($logPath, "--- EPHEMERAL SCRUB EXECUTED ---\n");
            }
        }
    }
}
