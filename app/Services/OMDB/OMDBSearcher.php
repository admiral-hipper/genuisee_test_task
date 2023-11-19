<?php

namespace App\Services\OMDB;

use App\Models\Movie;
use App\Models\Rating;

class OMDBSearcher
{
    public function __construct(private string $search, private string $type, private int $page)
    {
    }

    public function searchMovie(): array
    {
        $client = new OMBDApiClient(env('OMDB_API_KEY'));
        $movies = [];
        $forrmatter = new ResponseFormatter();
        $imdbIDs = $forrmatter->getImdbIDs($client->searchMovie($this->search, $this->type, $this->page));
        foreach ($imdbIDs as $id) {
            $response = $client->getFullMovieInfo($id);
            $movie = $forrmatter->getMovieFields($response);
            $moviesModel = Movie::firstOrCreate($movie);
            $ratingsModels = array_map(function ($item) {
                return new Rating($item);
            }, $forrmatter->getMovieRatings($response));
            $moviesModel->ratings()->delete();
            $moviesModel->ratings()->saveMany($ratingsModels);
            $movies[] = $moviesModel;
        }

        return $movies;
    }
}
