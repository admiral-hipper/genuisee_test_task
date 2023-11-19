<?php

namespace Tests\Feature\Api;

use App\Models\Movie;
use App\Models\Rating;
use Tests\ApiTestCase;

class ShowMoviesTest extends ApiTestCase
{
    public function test_can_show_stored_movies()
    {
        $movies = Movie::factory()->count(3)->has(Rating::factory()->count(2))->create();

        $this->graphQL(
            /** @lang GraphQL */
            '
            query {
                movies(first: 3,page:1){
                    data{
                        title
                }
                    paginatorInfo {
                    currentPage
                    lastPage
                }}
              }
            ',
            headers: ['Authorization' => 'Bearer '.$this->apiToken]
        )->assertJsonFragment([
            'data' => ['movies' => [
                'data' => [
                    ['title' => $movies[0]->title],
                    ['title' => $movies[1]->title],
                    ['title' => $movies[2]->title],
                ],
                'paginatorInfo' => [
                    'currentPage' => 1,
                    'lastPage' => 1,
                ],
            ]],
        ]);
    }

    public function test_can_find_movie_by_imdbID()
    {
        $movies = Movie::factory()->count(3)->has(Rating::factory()->count(1))->create();
        $this->graphQL(
            '
            query {
                movie(imdbID:"'.$movies[0]->imdbID.'"){
                        title
                        year
                        ratings{source}
                }
              }
            ',
            headers: ['Authorization' => 'Bearer '.$this->apiToken]
        )->assertJsonFragment([
            'data' => ['movie' => ['ratings' => [['source' => $movies[0]->ratings[0]->source]], 'title' => $movies[0]->title, 'year' => $movies[0]->year]],
        ]);
    }
}
