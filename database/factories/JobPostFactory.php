<?php

namespace Database\Factories;

use App\Models\JobPost;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\File;

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
        
        $images = ['01K88BABM23D2J7K6SAFA21B77.jpg','01K88BD68M8SDJDR4MD3F46B21.jpg'];
        return [
            'user_id' => 63,
            'title' => $this->faker->sentence(),
            'company' => $this->faker->company(),
            'designation' => $this->faker->jobTitle(),
            'job_type' => $this->faker->randomElement(['Full Time', 'Part Time', 'Remote', 'Contract']),
            'salary' => $this->faker->numberBetween(25000, 100000),
            'due_date' => $this->faker->date(),
            'link' => $this->faker->url(),
            'website_preview' => 1,
            'job_image' => 'events/' . $this->faker->randomElement($images),
            'address' => $this->faker->address(),
            'description' => $this->faker->sentence(),
        ];
    }
}
