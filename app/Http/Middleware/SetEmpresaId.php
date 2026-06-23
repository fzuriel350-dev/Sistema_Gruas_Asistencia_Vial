<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetEmpresaId
{
    public function handle(Request $request, Closure $next): Response
    {
        if ($user = $request->user()) {
            if ($user->empresa_id) {
                session(['empresa_id' => $user->empresa_id]);
            }
        }

        return $next($request);
    }
}
