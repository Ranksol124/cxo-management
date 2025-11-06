<?php

namespace Database\Factories;

use App\Models\Event;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Event>
 */
class EventFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = Event::class;
    public function definition(): array
    {
        $images = ['01K88BABM23D2J7K6SAFA21B77.jpg','01K88BD68M8SDJDR4MD3F46B21.jpg'];
        return [
            'user_id' => 62,
            'title'=>fake()->title(),
            'start_date'=>fake()->date(),
            'end_date'=>fake()->date(),
            'link'=>fake()->url(),
            'website_preview'=> 1,
            'event_image'=> 'images/' . fake()->randomElement($images),
            'description'=>fake()->paragraph(),
        ];
    }
}
