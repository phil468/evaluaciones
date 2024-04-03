<?php

namespace Database\Factories;

use App\Models\TipoDeTrabajador;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class TipoDeTrabajadorFactory extends Factory
{
    protected $model = TipoDeTrabajador::class;

    public function definition()
    {
        return [
			'idtipotrabajador_nisira' => $this->faker->name,
			'name' => $this->faker->name,
			'estado' => $this->faker->name,
			'empresa_id' => $this->faker->name,
        ];
    }
}
