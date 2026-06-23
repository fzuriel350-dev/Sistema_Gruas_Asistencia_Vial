<?php

namespace App\Models\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class TenantScope implements Scope
{
    public function apply(Builder $builder, Model $model): void
    {
        if ($empresaId = session('empresa_id')) {
            $builder->where($model->getTable() . '.empresa_id', $empresaId);
        }
    }
}
