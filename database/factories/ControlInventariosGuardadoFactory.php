<?php

namespace Database\Factories;

use App\Models\ControlInventariosGuardado;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ControlInventariosGuardadoFactory extends Factory
{
    protected $model = ControlInventariosGuardado::class;

    public function definition()
    {
        return [
			'fecha' => $this->faker->name,
			'user_id' => $this->faker->name,
        ];
    }
}
