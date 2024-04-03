<?php

namespace Database\Factories;

use App\Models\InventarioFisico;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class InventarioFisicoFactory extends Factory
{
    protected $model = InventarioFisico::class;

    public function definition()
    {
        return [
			'stock_virtual_id' => $this->faker->name,
			'stock_almacen' => $this->faker->name,
			'stock_mezzanine' => $this->faker->name,
			'reingreso' => $this->faker->name,
			'desmedro' => $this->faker->name,
			'merma_proveedor' => $this->faker->name,
			'observaciones' => $this->faker->name,
			'revisado' => $this->faker->name,
			'created_by' => $this->faker->name,
			'updated_by' => $this->faker->name,
			'deleted_by' => $this->faker->name,
        ];
    }
}
