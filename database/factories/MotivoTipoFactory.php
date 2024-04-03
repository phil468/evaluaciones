<?php

namespace Database\Factories;

use App\Models\MotivoTipo;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class MotivoTipoFactory extends Factory
{
    protected $model = MotivoTipo::class;

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
