<?php

namespace Database\Factories;

use App\Models\Opcione;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class OpcioneFactory extends Factory
{
    protected $model = Opcione::class;

    public function definition()
    {
        return [
			'pregunta_id' => $this->faker->name,
			'opcion' => $this->faker->name,
			'valor' => $this->faker->name,
			'optionid' => $this->faker->name,
        ];
    }
}
