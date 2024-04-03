<?php

namespace Database\Factories;

use App\Models\Seccione;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class SeccioneFactory extends Factory
{
    protected $model = Seccione::class;

    public function definition()
    {
        return [
			'name' => $this->faker->name,
			'color' => $this->faker->name,
        ];
    }
}
