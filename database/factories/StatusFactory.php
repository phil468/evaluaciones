<?php

namespace Database\Factories;

use App\Models\Status;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class StatusFactory extends Factory
{
    protected $model = Status::class;

    public function definition()
    {
        return [
			'name' => $this->faker->name,
			'color' => $this->faker->name,
			'estado' => $this->faker->name,
        ];
    }
}
