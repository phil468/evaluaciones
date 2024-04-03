<?php

namespace Database\Factories;

use App\Models\PagoTipo;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class PagoTipoFactory extends Factory
{
    protected $model = PagoTipo::class;

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
