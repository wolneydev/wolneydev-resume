<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureLocalWriteAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (! $this->isLocalRequest($request) || ! $this->hasValidWriteKey($request)) {
            return response()->json([
                'message' => 'Unauthorized. Write operations require a local request and a valid API key.',
            ], Response::HTTP_FORBIDDEN);
        }

        return $next($request);
    }

    private function isLocalRequest(Request $request): bool
    {
        return in_array($request->ip(), ['127.0.0.1', '::1'], true);
    }

    private function hasValidWriteKey(Request $request): bool
    {
        $expectedKey = (string) config('services.resume.write_key');

        if ($expectedKey === '') {
            return false;
        }

        $providedKey = (string) $request->header('X-Resume-Write-Key', '');

        return hash_equals($expectedKey, $providedKey);
    }
}
