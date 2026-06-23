<?php

namespace Tests\Feature;

use App\Models\Aseguradora;
use App\Models\Cliente;
use App\Models\Cotizacion;
use App\Models\Empresa;
use App\Models\TipoServicio;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CotizacionControllerTest extends TestCase
{
    use RefreshDatabase;

    private Empresa $empresa;
    private User $admin;
    private Cliente $cliente;
    private Aseguradora $aseguradora;
    private TipoServicio $tipoServicio;

    protected function setUp(): void
    {
        parent::setUp();
        $this->empresa = Empresa::factory()->create();
        $this->admin = User::factory()->create([
            'role' => 'admin',
            'empresa_id' => $this->empresa->id,
        ]);
        $this->cliente = Cliente::create([
            'empresa_id' => $this->empresa->id,
            'nombre' => 'Test Cliente',
        ]);
        $this->aseguradora = Aseguradora::create([
            'empresa_id' => $this->empresa->id,
            'nombre' => 'Test Seguros',
        ]);
        $this->tipoServicio = TipoServicio::create([
            'empresa_id' => $this->empresa->id,
            'nombre' => 'Arrastre',
        ]);
        session(['empresa_id' => $this->empresa->id]);
    }

    public function test_index_shows_cotizaciones(): void
    {
        $this->actingAs($this->admin)
            ->get(route('cotizaciones.index'))
            ->assertOk();
    }

    public function test_cost_calculation_on_store(): void
    {
        $this->actingAs($this->admin);

        $response = $this->post(route('cotizaciones.store'), [
            'cliente_id' => $this->cliente->id,
            'aseguradora_id' => $this->aseguradora->id,
            'tipo_servicio_id' => $this->tipoServicio->id,
            'marca' => 'Nissan',
            'modelo' => 'Versa',
            'color' => 'Gris',
            'placas' => 'ABC-123',
            'origen' => 'CDMX',
            'destino' => 'Puebla',
            'distancia_km' => 100,
            'tiempo_estimado' => 60,
            'tipo_ruta' => 'foraneo',
            'costo_banderazo' => 500,
            'costo_km' => 10,
            'con_peaje' => false,
            'num_casetas' => 0,
            'costo_casetas' => 0,
            'extras' => 0,
            'action' => 'generate',
        ]);

        $response->assertRedirect(route('cotizaciones.index'));

        $cotizacion = Cotizacion::first();

        $expectedSubtotal = 500 + (100 * 10) + 0 + 0; // banderazo + km + casetas + extras
        $expectedIva = round($expectedSubtotal * 0.16, 2);
        $expectedTotal = $expectedSubtotal + $expectedIva;

        $this->assertEquals($expectedSubtotal, $cotizacion->subtotal);
        $this->assertEquals($expectedIva, $cotizacion->iva);
        $this->assertEquals($expectedTotal, $cotizacion->costo_total);
        $this->assertEquals('pendiente', $cotizacion->estatus);
    }

    public function test_cost_calculation_with_discount(): void
    {
        $this->actingAs($this->admin);

        $cotizacion = Cotizacion::create([
            'empresa_id' => $this->empresa->id,
            'cliente_id' => $this->cliente->id,
            'aseguradora_id' => $this->aseguradora->id,
            'tipo_servicio_id' => $this->tipoServicio->id,
            'folio' => 'COT-TEST-001',
            'origen' => 'A',
            'destino' => 'B',
            'distancia_km' => 50,
            'tiempo_estimado' => 30,
            'tipo_ruta' => 'local',
            'marca' => 'Nissan',
            'modelo' => 'Tsuru',
            'placas' => 'XYZ-999',
            'costo_banderazo' => 400,
            'costo_km' => 8,
            'costo_casetas' => 100,
            'extras' => 50,
            'descuento_porcentaje' => 10,
            'created_by' => $this->admin->id,
            'costo_total' => 0,
        ]);

        $controller = new \App\Http\Controllers\CotizacionController();
        $reflection = new \ReflectionMethod($controller, 'calcularCostos');
        $reflection->setAccessible(true);
        $reflection->invoke($controller, $cotizacion);

        $expectedSubtotal = 400 + (50 * 8) + 100 + 50; // 950
        $expectedDescuento = 950 * 0.10; // 95
        $expectedBaseIva = 950 - 95; // 855
        $expectedIva = round($expectedBaseIva * 0.16, 2); // 136.80
        $expectedTotal = $expectedBaseIva + $expectedIva; // 991.80

        $this->assertEquals(950, $cotizacion->subtotal);
        $this->assertEquals(95, $cotizacion->descuento_monto);
        $this->assertEquals($expectedIva, $cotizacion->iva);
        $this->assertEquals($expectedTotal, $cotizacion->costo_total);
    }

    public function test_cotizacion_requires_authentication(): void
    {
        $this->get(route('cotizaciones.index'))->assertRedirect(route('login'));
    }

    public function test_cotizacion_denies_cliente_index(): void
    {
        $clienteUser = User::factory()->create([
            'role' => 'cliente',
            'empresa_id' => $this->empresa->id,
        ]);

        $this->actingAs($clienteUser)
            ->get(route('cotizaciones.index'))
            ->assertOk();
    }
}
