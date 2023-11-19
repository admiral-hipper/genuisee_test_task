<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Movie>
 */
class MovieFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'imdbID' => $this->faker->unique()->uuid,
            'title' => $this->faker->sentence,
            'type' => $this->faker->randomElement(['movie', 'series', 'episode']),
            'releasedDate' => $this->faker->date,
            'year' => $this->faker->year,
            'posterUrl' => $this->faker->imageUrl,
            'genre' => $this->faker->word,
            'runtime' => $this->faker->randomNumber(2).' mins',
            'country' => $this->faker->country,
            'imdbRating' => $this->faker->randomFloat(1, 1, 10),
            'imdbVotes' => $this->faker->numberBetween(100, 1000),
        ];
    }
}
