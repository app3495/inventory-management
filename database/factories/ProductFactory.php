<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'code' => substr($this->faker->name, 0, 4),
            'name' => $this->faker->name,
            'description' => substr($this->faker->paragraph, 0, 30),
            'price' => rand(100, 20000),
        ];
    }
}
