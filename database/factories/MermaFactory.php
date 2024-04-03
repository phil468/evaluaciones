<?php

namespace Database\Factories;

use App\Models\Merma;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class MermaFactory extends Factory
{
    protected $model = Merma::class;

    public function definition()
    {
        return [
			'name' => $this->faker->name,
			'descripcion' => $this->faker->name,
			'min' => $this->faker->name,
			'max' => $this->faker->name,
			'color' => $this->faker->name,
        ];
    }
}
