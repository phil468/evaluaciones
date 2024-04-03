<?php

namespace Database\Factories;

use App\Models\StockDocumento;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class StockDocumentoFactory extends Factory
{
    protected $model = StockDocumento::class;

    public function definition()
    {
        return [
			'documento' => $this->faker->name,
			'fecha' => $this->faker->name,
			'created_by' => $this->faker->name,
			'updated_by' => $this->faker->name,
			'deleted_by' => $this->faker->name,
        ];
    }
}
