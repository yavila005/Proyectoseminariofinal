<?php

namespace Database\Factories;
use App\Models\Alumno;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Alumno>
 */

class AlumnoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model=Alumno::class;
     public function definition(): array
    {
        return [
            'nombre'=>$this->faker->firstName,
            'apellido'=>$this->faker->lastName,
            'email'=>$this->faker->unique->safeEmail,
            'edad'=>$this->faker->numberBetween(18,30)  
        ];
    }
}
