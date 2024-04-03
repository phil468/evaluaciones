<?php

namespace Database\Factories;

use App\Models\InventarioHasPallet;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class InventarioHasPalletFactory extends Factory
{
    protected $model = InventarioHasPallet::class;

    public function definition()
    {
        return [
			'inventario_id' => $this->faker->name,
			'pallet_id' => $this->faker->name,
        ];
    }
}
