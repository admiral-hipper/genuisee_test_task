<?php

namespace Tests\Feature\Api;

use App\Models\Movie;
use App\Models\Rating;
use Tests\ApiTestCase;

class UpdateMovieTest extends ApiTestCase
{
    public function test_can_update_movie_via_api()
    {
        $movies = Movie::factory()->count(3)->has(Rating::factory()->count(1))->create();
        $imdbID = $movies[0]->imdbID;
        $this->graphQL(
            '
            mutation{
                updateMovie(movieInput:{
                     imdbID:"'.$imdbID.'",
                    title:"Test Api Case",
                    ratings:[{
                        source:"asdsa",
                        value:"testvalue"
                    }]
                }){title, ratings{source,value}}
            }
            
            ',
            headers: ['Authorization' => 'Bearer '.$this->apiToken]
        )->assertJsonFragment([
            'data' => ['updateMovie' => ['title' => 'Test Api Case', 'ratings' => [['source' => 'asdsa', 'value' => 'testvalue']]]],
        ]);

        $movies[0]->refresh();
        $this->assertEquals('Test Api Case', $movies[0]->title);
        $this->assertEquals(
            ['source' => 'asdsa', 'value' => 'testvalue'],
            $movies[0]->ratings[0]->only(['source', 'value'])
        );
    }
}
