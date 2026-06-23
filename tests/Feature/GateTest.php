<?php

namespace Tests\Feature;

use App\Models\Empresa;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GateTest extends TestCase
{
    use RefreshDatabase;

    private Empresa $empresa;

    protected function setUp(): void
    {
        parent::setUp();
        $this->empresa = Empresa::factory()->create();
        session(['empresa_id' => $this->empresa->id]);
    }

    public function test_admin_gate_allows_admin(): void
    {
        $user = User::factory()->create(['role' => 'admin', 'empresa_id' => $this->empresa->id]);
        $this->actingAs($user);
        $this->assertTrue($user->can('admin'));
    }

    public function test_admin_gate_denies_cotizador(): void
    {
        $user = User::factory()->create(['role' => 'cotizador', 'empresa_id' => $this->empresa->id]);
        $this->actingAs($user);
        $this->assertFalse($user->can('admin'));
    }

    public function test_admin_gate_denies_operador(): void
    {
        $user = User::factory()->create(['role' => 'operador', 'empresa_id' => $this->empresa->id]);
        $this->actingAs($user);
        $this->assertFalse($user->can('admin'));
    }

    public function test_empleado_gate_allows_admin(): void
    {
        $user = User::factory()->create(['role' => 'admin', 'empresa_id' => $this->empresa->id]);
        $this->actingAs($user);
        $this->assertTrue($user->can('empleado'));
    }

    public function test_empleado_gate_allows_cotizador(): void
    {
        $user = User::factory()->create(['role' => 'cotizador', 'empresa_id' => $this->empresa->id]);
        $this->actingAs($user);
        $this->assertTrue($user->can('empleado'));
    }

    public function test_empleado_gate_allows_operador(): void
    {
        $user = User::factory()->create(['role' => 'operador', 'empresa_id' => $this->empresa->id]);
        $this->actingAs($user);
        $this->assertTrue($user->can('empleado'));
    }

    public function test_empleado_gate_denies_cliente(): void
    {
        $user = User::factory()->create(['role' => 'cliente', 'empresa_id' => $this->empresa->id]);
        $this->actingAs($user);
        $this->assertFalse($user->can('empleado'));
    }
}
