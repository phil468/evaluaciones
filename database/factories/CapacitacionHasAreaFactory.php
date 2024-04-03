<?php

namespace Database\Factories;

use App\Models\CapacitacionHasArea;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class CapacitacionHasAreaFactory extends Factory
{
    protected $model = CapacitacionHasArea::class;

    public function definition()
    {
        return [
			'area_id' => $this->faker->name,
			'capacitacion_id' => $this->faker->name,
        ];
    }
}
