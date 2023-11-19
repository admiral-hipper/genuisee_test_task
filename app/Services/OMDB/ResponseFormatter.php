<?php

namespace App\Services\OMDB;

use App\Services\OMDB\DataWrappers\FullMovieInfoWrapper;
use App\Services\OMDB\DataWrappers\SearchMovieWrapper;

class ResponseFormatter
{
    public function __construct()
    {
    }

    public function getMovieFields(FullMovieInfoWrapper $response): array
    {
        return [
            'imdbID' => $response->imdbID,
            'title' => $response->Title,
            'type' => $response->Type,
            'releasedDate' => $response->Released,
            'year' => $response->Year,
            'posterUrl' => $response->Poster,
            'genre' => $response->Genre,
            'runtime' => $response->Runtime,
            'country' => $response->Country,
            'imdbRating' => $response->imdbRating,
            'imdbVotes' => $response->imdbVotes,
        ];
    }

    public function getMovieRatings(FullMovieInfoWrapper $response): array
    {
        return array_map(function ($item) {
            return [
                'source' => $item->Source,
                'value' => $item->Value,
            ];
        }, $response->Ratings);
    }

    public function getImdbIDs(SearchMovieWrapper $response): array
    {
        return collect($response->Search)->map(fn ($item) => $item->imdbID)->toArray();
    }
}
