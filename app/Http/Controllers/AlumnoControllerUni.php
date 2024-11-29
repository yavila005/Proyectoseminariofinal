<?php

namespace Tests\Unit;

use App\Models\Alumno;
use App\Http\Controllers\AlumnoController;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Request;
use Tests\TestCase;

class AlumnoControllerUnitTest extends TestCase
{
    public function test_fallido_crear_un_alumno()
    {
        // Crear una instancia del controlador
        $controller = new AlumnoController();
        
        // Crear una solicitud con datos vacíos
        $request = Request::create('/alumnos', 'POST', [
            'nombre' => '', // Nombre vacío para comprobar la validación
            'apellido' => '',
            'email' => '',
            'edad' => ''
        ]);

        // Se espera que la validación falle
        $this->expectException(ValidationException::class);

        // Ejecutar el método store, lo que debería lanzar una excepción
        $controller->store($request);
    }

    public function test_crear_un_alumno()
    {
        // Crear una instancia del controlador
        $controller = new AlumnoController();

        // Crear una solicitud con datos válidos
        $request = Request::create('/alumnos', 'POST', [
            'nombre' => 'Javier',
            'apellido' => 'Avila',
            'email' => 'javierjosueavila@gmail.com',
            'edad' => '23'
        ]);

        // Ejecutar el método store
        $response = $controller->store($request);

        // Verificar que se redirige correctamente después de la creación
        $this->assertEquals(route('alumnos.index'), $response->headers->get('Location'));
    }

    public function test_assert_false_con_datos_invalidos()
    {
        // Crear una instancia del controlador
        $controller = new AlumnoController();

        // Crear una solicitud con datos inválidos
        $request = Request::create('/alumnos', 'POST', [
            'nombre' => '',
            'apellido' => 'avila',
            'email' => 'correo_invalido',
            'edad' => 'no_numerico'
        ]);

        try {
            // Intentar ejecutar el método store
            $controller->store($request);
            $this->fail('La validación debería haber fallado, pero no lo hizo.');
        } catch (ValidationException $e) {
            // Verificar que la excepción contiene errores específicos
            $this->assertFalse(empty($e->errors()));
        }
    }

    public function test_assert_same_con_edad()
    {
        // Crear una instancia de Alumno
        $alumno = new Alumno([
            'nombre' => 'Juan',
            'apellido' => 'Pérez',
            'email' => 'juanperez@example.com',
            'edad' => '25'
        ]);

        // Verificar que la edad es exactamente igual (tipo y valor)
        $this->assertSame('25', $alumno->edad);
    }

    public function test_assert_equals_con_email()
    {
        // Crear una instancia de Alumno
        $alumno = new Alumno([
            'nombre' => 'Angel',
            'apellido' => 'gomez',
            'email' => 'angelgz@example.com',
            'edad' => '22'
        ]);

        // Verificar que el email es igual al proporcionado
        $this->assertEquals('angelgz@example.com', $alumno->email);
    }

    public function test_assert_is_numeric_en_edad()
    {
        // Crear una instancia de Alumno
        $alumno = new Alumno([
            'nombre' => 'Carlos',
            'apellido' => 'Amador',
            'email' => 'carlosamador@example.com',
            'edad' => 30
        ]);

        // Verificar que la edad es un valor numérico
        $this->assertIsNumeric($alumno->edad);
    }
}

