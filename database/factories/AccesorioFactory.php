<?php

namespace Database\Factories;

use App\Models\Accesorio;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class AccesorioFactory extends Factory
{
    protected $model = Accesorio::class;

    public function definition()
    {
        return [
			'name' => $this->faker->name,
			'estado' => $this->faker->name,
			'stock' => $this->faker->name,
        ];
    }
}
