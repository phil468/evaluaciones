<?php

namespace Database\Factories;

use App\Models\Productore;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ProductoreFactory extends Factory
{
    protected $model = Productore::class;

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
