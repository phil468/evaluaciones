<?php

namespace Database\Factories;

use App\Models\Personal;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class PersonalFactory extends Factory
{
    protected $model = Personal::class;

    public function definition()
    {
        return [
			'dni' => $this->faker->name,
			'name' => $this->faker->name,
			'nombres' => $this->faker->name,
			'apellido_paterno' => $this->faker->name,
			'apellido_materno' => $this->faker->name,
			'empresa_id' => $this->faker->name,
			'gerencia_id' => $this->faker->name,
			'area_id' => $this->faker->name,
			'cargo_id' => $this->faker->name,
			'correo_empresa' => $this->faker->name,
			'celular_empresa' => $this->faker->name,
			'correo_personal' => $this->faker->name,
			'telefono_personal' => $this->faker->name,
			'celular_personal' => $this->faker->name,
			'foto' => $this->faker->name,
			'estado' => $this->faker->name,
			'genero' => $this->faker->name,
        ];
    }
}
