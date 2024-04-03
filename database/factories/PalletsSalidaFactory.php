<?php

namespace Database\Factories;

use App\Models\PalletsSalida;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class PalletsSalidaFactory extends Factory
{
    protected $model = PalletsSalida::class;

    public function definition()
    {
        return [
			'fecha' => $this->faker->name,
			'observaciones' => $this->faker->name,
        ];
    }
}
