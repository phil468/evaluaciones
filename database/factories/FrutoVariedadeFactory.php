<?php

namespace Database\Factories;

use App\Models\FrutoVariedade;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class FrutoVariedadeFactory extends Factory
{
    protected $model = FrutoVariedade::class;

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
