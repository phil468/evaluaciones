<?php

namespace Database\Factories;

use App\Models\Planilla;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class PlanillaFactory extends Factory
{
    protected $model = Planilla::class;

    public function definition()
    {
        return [
			'name' => $this->faker->name,
			'estado' => $this->faker->name,
        ];
    }
}
