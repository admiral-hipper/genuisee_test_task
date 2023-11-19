<?php

namespace Tests\Feature\Api;

use App\Models\Movie;
use App\Models\Rating;
use Tests\ApiTestCase;
use VCR\VCR;

class SearchMoviesTest extends ApiTestCase
{
    public function setUp(): void
    {
        parent::setUp();
        VCR::configure()->setMode(VCR::MODE_ONCE);
        VCR::configure()->setCassettePath('tests/fixtures');
        VCR::configure()->enableRequestMatchers(['query_string']);
        VCR::turnOn();
    }

    public function test_can_store_movies_after_search()
    {
        VCR::insertCassette('positive_search_movies_mutation.yml');
        $this->graphQL(
            /** @lang GraphQL */
            '
        
            mutation {
                searchMovies(search:"Lock, Stock and Two Smoking Barrels", type: movie, page:1){
                        imdbID title ratings{source value} year
                }
           }
        ',
            headers: ['Authorization' => 'Bearer '.$this->apiToken]
        )->assertJson(
            json_decode(
                /** @lang Json */
                '

        {
            "data": {
                "searchMovies": [
                    {
                        "imdbID": "tt0120735",
                        "title": "Lock, Stock and Two Smoking Barrels",
                        "ratings": [
                            {
                                "source": "Internet Movie Database",
                                "value": "8.1/10"
                            },
                            {
                                "source": "Rotten Tomatoes",
                                "value": "75%"
                            },
                            {
                                "source": "Metacritic",
                                "value": "66/100"
                            }
                        ],
                        "year": "1998"
                    },
                    {
                        "imdbID": "tt3522962",
                        "title": "Making of Lock Stock and Two Smoking Barrels",
                        "ratings": [
                            {
                                "source": "Internet Movie Database",
                                "value": "7.4/10"
                            }
                        ],
                        "year": "1998"
                    }
                ]
            }
        }
        ', true)
        );

        $this->assertEquals(2, Movie::count());
        $this->assertEquals(4, Rating::count());
    }
}
