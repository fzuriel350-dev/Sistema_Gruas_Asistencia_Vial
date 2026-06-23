<?php

namespace App\Models\Traits;

use App\Models\Scopes\TenantScope;

trait BelongsToEmpresa
{
    protected static function bootBelongsToEmpresa(): void
    {
        static::addGlobalScope(new TenantScope);
    }
}
