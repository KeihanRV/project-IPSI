<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence(3),
            'description' => $this->faker->paragraph(),
            'specification' => $this->faker->text(200),
            'location' => $this->faker->city(),
            'image' => 'placeholder.png',
            'rating' => $this->faker->randomFloat(1, 0, 5),
            'sold' => $this->faker->numberBetween(0, 1000),
        ];
    }
}
