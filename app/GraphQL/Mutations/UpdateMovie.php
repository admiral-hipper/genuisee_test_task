<?php

namespace App\GraphQL\Mutations;

use App\Exceptions\UpdateMovieException;
use App\Models\Movie;
use App\Models\Rating;

final readonly class UpdateMovie
{
    /** @param  array{}  $args */
    public function __invoke(null $_, array $args)
    {
        if (! ($movie = Movie::find($args['movieInput']['imdbID']))) {
            throw new UpdateMovieException('Movie with imdbID '.$args['movieInput']['imdbID'].' not found');
        }
        foreach ($args['movieInput'] as $field => $value) {
            if ($field == 'ratings') {
                $movie->ratings()->delete();
                $ratingsModels = array_map(function ($rating) {
                    return new Rating($rating);
                }, $value);
                $movie->ratings()->saveMany($ratingsModels);

                continue;
            }
            $movie->$field = $value;
        }
        $movie->save();

        return $movie;
    }
}
