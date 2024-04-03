<?php

namespace Database\Factories;

use App\Models\ControlInventariosRevisado;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ControlInventariosRevisadoFactory extends Factory
{
    protected $model = ControlInventariosRevisado::class;

    public function definition()
    {
        return [
			'fecha' => $this->faker->name,
			'user_id' => $this->faker->name,
        ];
    }
}
