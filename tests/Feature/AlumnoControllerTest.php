<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Producto;
use App\Models\Categoria;

class ProductoControllerTest extends TestCase
{
    //use RefreshDatabase;

    /** @test */
    public function puede_crear_un_producto()
    {
        // Crear una categoría para asociarla al producto
        $categoria = Categoria::factory()->create();

        // Realizar la petición POST para crear un producto
        $response = $this->post('/productos', [
            'nombre' => 'Laptop',
            'descripcion' => 'Laptop de alta gama',
            'precio' => 1500.50,
            'stock' => 10,
            'categoria_id' => $categoria->id,
        ]);

        // Verificar que redirige a la lista de productos
        $response->assertRedirect('/productos');

        // Verificar que el producto se haya guardado en la base de datos
        $this->assertDatabaseHas('productos', [
            'nombre' => 'Laptop',
            'descripcion' => 'Laptop de alta gama',
            'precio' => 1500.50,
            'stock' => 10,
            'categoria_id' => $categoria->id,
        ]);
    }

    /** @test */
    public function puede_mostrar_detalles_de_un_producto()
    {
        // Crear un producto utilizando una fábrica
        $producto = Producto::factory()->create();

        // Realizar la petición GET para ver los detalles del producto
        $response = $this->get("/productos/{$producto->id}");

        // Verificar que la solicitud fue exitosa
        $response->assertStatus(200);
        $response->assertSee($producto->nombre);
        $response->assertSee($producto->descripcion);
        $response->assertSee($producto->precio);
        $response->assertSee($producto->stock);
    }

    /** @test */
    public function puede_actualizar_un_producto()
    {
        // Crear un producto inicial
        $producto = Producto::factory()->create([
            'nombre' => 'Teclado',
            'descripcion' => 'Teclado mecánico',
            'precio' => 50.00,
            'stock' => 15,
        ]);

        // Realizar la petición PUT para actualizar el producto
        $response = $this->put("/productos/{$producto->id}", [
            'nombre' => 'Teclado Inalámbrico',
            'descripcion' => 'Teclado mecánico inalámbrico',
            'precio' => 75.00,
            'stock' => 20,
            'categoria_id' => $producto->categoria_id,
        ]);

        // Verificar que redirige a la lista de productos
        $response->assertRedirect('/productos');

        // Verificar que los cambios se reflejan en la base de datos
        $this->assertDatabaseHas('productos', [
            'id' => $producto->id,
            'nombre' => 'Teclado Inalámbrico',
            'descripcion' => 'Teclado mecánico inalámbrico',
            'precio' => 75.00,
            'stock' => 20,
        ]);

        // Verificar que la versión anterior ya no existe
        $this->assertDatabaseMissing('productos', [
            'id' => $producto->id,
            'nombre' => 'Teclado',
            'descripcion' => 'Teclado mecánico',
            'precio' => 50.00,
            'stock' => 15,
        ]);
    }

    /** @test */
    public function puede_eliminar_un_producto()
    {
        // Crear un producto para eliminar
        $producto = Producto::factory()->create();

        // Realizar la petición DELETE para eliminar el producto
        $response = $this->delete("/productos/{$producto->id}");

        // Verificar que redirige a la lista de productos
        $response->assertRedirect('/productos');

        // Verificar que el producto esté "eliminado" con soft delete
        $this->assertSoftDeleted('productos', [
            'id' => $producto->id,
        ]);
    }
}
