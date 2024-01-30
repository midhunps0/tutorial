<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Product;
use App\Models\Tag;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
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
            'categoryId' => Category::all()->random()->id,
            'name' => $this->faker->words(2, true),
            'description' => $this->faker->sentences(3, true),
            'price' => random_int(10000, 200000)/100
        ];
    }

    public function configure(): static
    {
        return $this->afterCreating(function (Product $p) {
            $tagIds = Tag::all()->pluck('id')->shuffle()->toArray();
            $x = random_int(1, 3);
            $theIds = array_slice($tagIds, 0, $x);
            $p->tags()->sync($theIds);
        });
    }
}
