<?php

namespace Tests\Unit\Services;

use App\Services\OMDB\DataWrappers\FullMovieInfoWrapper;
use App\Services\OMDB\DataWrappers\SearchMovieWrapper;
use App\Services\OMDB\OMBDApiClient;
use PHPUnit\Framework\TestCase;
use VCR\VCR;

class OmdbApiTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
        VCR::configure()->setMode(VCR::MODE_ONCE);
        VCR::configure()->setCassettePath('tests/fixtures');
        VCR::configure()->enableRequestMatchers(['body', 'url']);
        VCR::turnOn();
    }

    public function test_omdb_service_can_get_imdb_ids()
    {
        VCR::insertCassette('positive_omdb_movies_ids_search.yml');
        $client = new OMBDApiClient(env('OMDB_API_KEY'));
        $response = $client->searchMovie('Star Wars', 'movie', 1);
        $this->assertInstanceOf(SearchMovieWrapper::class, $response);
        $this->assertEquals(10, count($response->Search));
    }

    public function test_omdb_service_can_get_full_movie_info()
    {
        VCR::insertCassette('positive_omdb_full_movie_info.yml');
        $client = new OMBDApiClient(env('OMDB_API_KEY'));
        $response = $client->getFullMovieInfo('tt0796366');
        $this->assertInstanceOf(FullMovieInfoWrapper::class, $response);
        $this->assertEquals('Star Trek', $response->Title);
        $this->assertEquals('7.9', $response->imdbRating);
        $this->assertEquals(3, count($response->Ratings));
    }

    public function tearDown(): void
    {
        VCR::eject();
        VCR::turnOff();
        parent::tearDown();
    }
}
