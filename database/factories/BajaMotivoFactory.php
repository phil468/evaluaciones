<?php

namespace Database\Factories;

use App\Models\BajaMotivo;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class BajaMotivoFactory extends Factory
{
    protected $model = BajaMotivo::class;

    public function definition()
    {
        return [
			'name' => $this->faker->name,
			'estado' => $this->faker->name,
        ];
    }
}
