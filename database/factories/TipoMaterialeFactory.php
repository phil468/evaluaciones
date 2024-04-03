<?php

namespace Database\Factories;

use App\Models\TipoMateriale;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class TipoMaterialeFactory extends Factory
{
    protected $model = TipoMateriale::class;

    public function definition()
    {
        return [
			'name' => $this->faker->name,
        ];
    }
}
