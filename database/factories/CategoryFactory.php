<?php

namespace Database\Factories;
// 
use Illuminate\Support\Str;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Category>
 */
class CategoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // $name = 'Category No-' . random_int(1, 9999);
        // return [
        //     'name' => $name,
        //     'slug' => Str::slug($name),
        //     'description' => fake()->paragraph(),
        //     'image' => fake()->word() . '.png'
        // ];

        // ----------------------------

        // $name = 'Category No-' . $this->faker->sentence();
        $name = 'Category No-' . $this->faker->sentence();
        return [
            'name' => $name,
            'slug' => Str::slug($name),
            'description' => fake()->paragraph(),
            // 'image' => fake()->word() . '.png'
            'image' => $this->faker->uuid() . '.png'

        ];
    }
}
