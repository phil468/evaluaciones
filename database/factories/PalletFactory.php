<?php

namespace Database\Factories;

use App\Models\Pallet;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class PalletFactory extends Factory
{
    protected $model = Pallet::class;

    public function definition()
    {
        return [
			'kardex_pallet_id' => $this->faker->name,
			'correlativo' => $this->faker->name,
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
			'procedencia_id' => $this->faker->name,
			'productor_id' => $this->faker->name,
        ];
    }
}
