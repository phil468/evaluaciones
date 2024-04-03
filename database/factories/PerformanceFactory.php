<?php

namespace Database\Factories;

use App\Models\Performance;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class PerformanceFactory extends Factory
{
    protected $model = Performance::class;

    public function definition()
    {
        return [
			'name' => $this->faker->name,
			'estado' => $this->faker->name,
        ];
    }
}
