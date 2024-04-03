<?php

namespace Database\Factories;

use App\Models\PalletsSalidasDetalle;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class PalletsSalidasDetalleFactory extends Factory
{
    protected $model = PalletsSalidasDetalle::class;

    public function definition()
    {
        return [
			'pallets_salida_id' => $this->faker->name,
			'pallet_id' => $this->faker->name,
			'peso' => $this->faker->name,
			'numero_jabas' => $this->faker->name,
			'peso_unitario' => $this->faker->name,
			'tara_jabas' => $this->faker->name,
			'tara_parihuela' => $this->faker->name,
			'peso_parihuela' => $this->faker->name,
			'peso_neto_parihuela' => $this->faker->name,
			'jaba_tipo_id' => $this->faker->name,
			'parihuela_tipo_id' => $this->faker->name,
			'fruto_variedad_id' => $this->faker->name,
			'fruto_color_id' => $this->faker->name,
			'productor_id' => $this->faker->name,
			'procedencia_id' => $this->faker->name,
        ];
    }
}
