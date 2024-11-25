<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Topic;
use App\Support\PostFixtures;
use Illuminate\Support\Collection;
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
            'user_id' => User::factory(),
            'topic_id' => Topic::factory(),
            'title'=> str(fake()->sentence)->beforeLast('.')->title(),
            'body' => Collection::times(4, fn () => fake()->realText(1250))->join(PHP_EOL . PHP_EOL),
            'likes_count' => 0,
        ];
    }

    public function withFixtures(): static
    {
        $posts = PostFixtures::getFixtures();

        return $this->sequence(...$posts);
    }
}
