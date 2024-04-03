<?php

namespace Database\Factories;

use App\Models\StockVirtuale;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class StockVirtualeFactory extends Factory
{
    protected $model = StockVirtuale::class;

    public function definition()
    {
        return [
			'stock_documento_id' => $this->faker->name,
			'material_id' => $this->faker->name,
			'stock_virtual' => $this->faker->name,
			'merma' => $this->faker->name,
        ];
    }
}
