<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class blockNonStandardChars
{
    protected array $except = [
        'password',
        'password_confirmation',
    ];

    public function handle(Request $request, Closure $next): Response
    {
        if ($this->containsInvalidChars($request->all())) {
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Input mengandung karakter invalid.'
                ], 422);
            }
            abort(422, 'Input mengandung karakter invalid.');
        }

        return $next($request);
    }

    private function containsInvalidChars(array $data): bool
    {
        foreach ($data as $key => $value) {
            if (in_array($key, $this->except, true)) {
                continue;
            }

            if (is_array($value)) {
                if ($this->containsInvalidChars($value)) {
                    return true;
                }
            } elseif (is_string($value)) {
                if (!preg_match('/^[a-zA-Z0-9\s.,!?@#\$%\^&\*\(\)\-_\+=\[\]\{\}:;"\'<>\\/|]*$/', $value)) {
                    return true;
                }
            }
        }

        return false;
    }
}
