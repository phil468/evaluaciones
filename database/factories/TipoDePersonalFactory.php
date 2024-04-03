<?php

namespace Database\Factories;

use App\Models\TipoDePersonal;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class TipoDePersonalFactory extends Factory
{
    protected $model = TipoDePersonal::class;

    public function definition()
    {
        return [
			'idtipopersonal_nisira' => $this->faker->name,
			'name' => $this->faker->name,
			'estado' => $this->faker->name,
			'empresa_id' => $this->faker->name,
        ];
    }
}
