<?php

namespace Database\Factories;

use App\Models\Impresora;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ImpresoraFactory extends Factory
{
    protected $model = Impresora::class;

    public function definition()
    {
        return [
			'ip' => $this->faker->name,
        ];
    }
}
