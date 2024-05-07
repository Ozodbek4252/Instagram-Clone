<?php

namespace Database\Factories;

use App\Models\Post;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;
use App\Models\Media;

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
            'user_id' => User::factory(),
            'description' => $this->faker->sentence(),
            'location' => $this->faker->city(),
            'hide_like_view' => $this->faker->boolean(),
            'allow_commenting' => $this->faker->boolean(),
            'type' => $this->faker->randomElement(['post', 'reel']),
        ];
    }

    function configure()
    {
        return $this->afterCreating(function (Post $post) {
            if ($post->type === 'reel') {
                // Media::factory()->real()->create([
                //     'mediable_type' => get_class($post),
                //     'mediable_id' => $post->id,
                // ]);
            } else {
                // Media::factory()->post()->create([
                //     'mediable_type' => get_class($post),
                //     'mediable_id' => $post->id,
                // ]);
            }
        });
    }
}
