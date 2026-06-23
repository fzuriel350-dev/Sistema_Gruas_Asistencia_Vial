<?php

namespace Tests\Feature;

use App\Models\Cliente;
use App\Models\Empresa;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TenantScopeTest extends TestCase
{
    use RefreshDatabase;

    public function test_scope_filters_by_session_empresa_id(): void
    {
        $empresa1 = Empresa::factory()->create(['nombre' => 'Empresa 1']);
        $empresa2 = Empresa::factory()->create(['nombre' => 'Empresa 2']);

        session(['empresa_id' => $empresa1->id]);

        Cliente::create(['empresa_id' => $empresa1->id, 'nombre' => 'Cliente A']);
        Cliente::create(['empresa_id' => $empresa2->id, 'nombre' => 'Cliente B']);

        $this->assertCount(1, Cliente::all());
        $this->assertEquals('Cliente A', Cliente::first()->nombre);
    }

    public function test_scope_does_not_apply_without_session(): void
    {
        $empresa = Empresa::factory()->create();
        session()->forget('empresa_id');

        Cliente::create(['empresa_id' => $empresa->id, 'nombre' => 'Test']);

        $this->assertCount(1, Cliente::withoutGlobalScopes()->get());
    }
}
