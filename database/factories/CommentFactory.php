<?php

namespace Database\Factories;

use App\Models\Article;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class CommentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'value' => $this->faker->numberBetween($min=1, $max=5),
            'description' => $this->faker->realText(255),
            'user_id' => User::all()->random()->id,
            'article_id' => Article::all()->random()->id,
        ];
    }
}
