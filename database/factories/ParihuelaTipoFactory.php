<?php

namespace Database\Factories;

use App\Models\ParihuelaTipo;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ParihuelaTipoFactory extends Factory
{
    protected $model = ParihuelaTipo::class;

    public function definition()
    {
        return [
			'name' => $this->faker->name,
			'state' => $this->faker->name,
			'valor' => $this->faker->name,
			'fecha' => $this->faker->name,
			'updated_date' => $this->faker->name,
			'type' => $this->faker->name,
        ];
    }
}
