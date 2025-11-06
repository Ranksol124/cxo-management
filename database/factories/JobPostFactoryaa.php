<?php

namespace Database\Factories;

use App\Models\JobPost;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\JobPost>
 */
class JobPostFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = JobPost::class;
    public function definition(): array
    {
        $images = ['expo.jpg','event.jpg'];
        return [
            'user_id' => 63,
            'title' => fake()->sentence(),
            'company' => fake()->company(),
            'designation' => fake()->jobTitle(),
            'job_type' => fake()->randomElement(['Full Time','Part Time','Remote','Contract']),
            'salary' => fake()->numberBetween(25000,100000),
            'due_date' => fake()->date(),
            'link' => fake()->url(),
            'website_preview' => 1,
            'image' => '/images/' . fake()->randomElement($images),
            'address' => fake()->address(),
            'description' => fake()->paragraph(),
        ];
    }
}
