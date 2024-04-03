<?php

namespace Database\Factories;

use App\Models\Evaluacione;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class EvaluacioneFactory extends Factory
{
    protected $model = Evaluacione::class;

    public function definition()
    {
        return [
			'eid' => $this->faker->name,
			'title' => $this->faker->name,
			'date' => $this->faker->name,
			'status' => $this->faker->name,
        ];
    }
}
