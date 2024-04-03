<?php

namespace Database\Factories;

use App\Models\Vigencium;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class VigenciumFactory extends Factory
{
    protected $model = Vigencium::class;

    public function definition()
    {
        return [
			'name' => $this->faker->name,
			'estado' => $this->faker->name,
        ];
    }
}
