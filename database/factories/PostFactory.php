<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Post>
 */
class PostFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence(6),
            'slug' => $this->faker->slug(),
            'mobiledoc' => json_encode([
                [
                    'type' => 'text',
                    'content' => $this->faker->paragraphs(3, true)
                ],
                [
                    'type' => 'image',
                    'content' => $this->faker->imageUrl()
                ]
            ]),
            'type' => 'post',
            'status' => 'published',
        ];
    }
}
