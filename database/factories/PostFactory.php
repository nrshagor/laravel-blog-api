<?php

namespace Database\Factories;

use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class PostFactory extends Factory
{
    protected $model = Post::class;

    public function definition()
    {
        return [
            'title' => $this->faker->sentence, // Use a valid format
            'body' => $this->faker->paragraph, // Ensure this format is valid as well
            'user_id' => User::factory(), // Assuming you have a User factory
        ];
    }
}
