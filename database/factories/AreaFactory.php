<?php

namespace Database\Factories;

use App\Models\Area;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class AreaFactory extends Factory
{
    protected $model = Area::class;

    public function definition()
    {
        return [
			'name' => $this->faker->name,
			'estado' => $this->faker->name,
			'idempresa_nisira' => $this->faker->name,
			'idarea_nisira' => $this->faker->name,
			'fechacreacion_nisira' => $this->faker->name,
        ];
    }
}
