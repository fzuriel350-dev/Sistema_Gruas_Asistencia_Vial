<?php

namespace Tests\Feature;

use App\Models\Cliente;
use App\Models\Empresa;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ClienteControllerTest extends TestCase
{
    use RefreshDatabase;

    private Empresa $empresa;
    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->empresa = Empresa::factory()->create();
        $this->admin = User::factory()->create([
            'role' => 'admin',
            'empresa_id' => $this->empresa->id,
        ]);
        session(['empresa_id' => $this->empresa->id]);
    }

    public function test_index_shows_clientes(): void
    {
        Cliente::create([
            'empresa_id' => $this->empresa->id,
            'nombre' => 'Juan Pérez',
        ]);

        $this->actingAs($this->admin)
            ->get(route('clientes.index'))
            ->assertOk()
            ->assertSee('Juan Pérez');
    }

    public function test_create_form_is_accessible(): void
    {
        $this->actingAs($this->admin)
            ->get(route('clientes.create'))
            ->assertOk();
    }

    public function test_store_creates_cliente(): void
    {
        $this->actingAs($this->admin)
            ->post(route('clientes.store'), [
                'nombre' => 'María López',
                'telefono' => '5512345678',
                'empresa' => 'Empresa X',
                'contacto' => 'María',
                'direccion' => 'Calle 123',
            ])
            ->assertRedirect(route('clientes.index'));

        $this->assertDatabaseHas('clientes', [
            'nombre' => 'María López',
            'empresa_id' => $this->empresa->id,
        ]);
    }

    public function test_store_validates_required_fields(): void
    {
        $this->actingAs($this->admin)
            ->post(route('clientes.store'), [])
            ->assertSessionHasErrors('nombre');
    }

    public function test_show_displays_cliente(): void
    {
        $cliente = Cliente::create([
            'empresa_id' => $this->empresa->id,
            'nombre' => 'Carlos Ruiz',
        ]);

        $this->actingAs($this->admin)
            ->get(route('clientes.show', $cliente))
            ->assertOk()
            ->assertSee('Carlos Ruiz');
    }

    public function test_edit_form_is_accessible(): void
    {
        $cliente = Cliente::create([
            'empresa_id' => $this->empresa->id,
            'nombre' => 'Ana Martínez',
        ]);

        $this->actingAs($this->admin)
            ->get(route('clientes.edit', $cliente))
            ->assertOk();
    }

    public function test_update_modifies_cliente(): void
    {
        $cliente = Cliente::create([
            'empresa_id' => $this->empresa->id,
            'nombre' => 'Nombre Original',
        ]);

        $this->actingAs($this->admin)
            ->put(route('clientes.update', $cliente), [
                'nombre' => 'Nombre Actualizado',
            ])
            ->assertRedirect(route('clientes.index'));

        $this->assertDatabaseHas('clientes', [
            'id' => $cliente->id,
            'nombre' => 'Nombre Actualizado',
        ]);
    }

    public function test_destroy_removes_cliente(): void
    {
        $cliente = Cliente::create([
            'empresa_id' => $this->empresa->id,
            'nombre' => 'Eliminar Este',
        ]);

        $this->actingAs($this->admin)
            ->delete(route('clientes.destroy', $cliente))
            ->assertRedirect(route('clientes.index'));

        $this->assertDatabaseMissing('clientes', ['id' => $cliente->id]);
    }

    public function test_cliente_denies_cliente_role(): void
    {
        $clienteUser = User::factory()->create([
            'role' => 'cliente',
            'empresa_id' => $this->empresa->id,
        ]);

        $this->actingAs($clienteUser)
            ->get(route('clientes.index'))
            ->assertForbidden();
    }
}
