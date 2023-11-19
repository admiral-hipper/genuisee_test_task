<?php

namespace App\Services\OMDB;

use App\Exceptions\SearchMovieException;
use App\Services\OMDB\DataWrappers\FullMovieInfoWrapper;
use App\Services\OMDB\DataWrappers\SearchMovieWrapper;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Fluent;

class OMBDApiClient
{
    const OMBD_BASE_URL = 'http://www.omdbapi.com';

    public function __construct(private string $apiKey)
    {
    }

    public function searchMovie(string $search, string $type, int $page): SearchMovieWrapper
    {
        try {
            $client = new Client();

            $params = [
                'apiKey' => $this->apiKey,
                's' => $search,
                'type' => 'movie',
                'page' => $page,
            ];

            $response = $client->request('GET', self::OMBD_BASE_URL.'?'
                .http_build_query($params))->getBody()->getContents();
            $response = new SearchMovieWrapper(json_decode($response));

            $this->checkErrors($response);

            return $response;
        } catch (RequestException $e) {
            Log::error('OMDB searchMovie request failed. Request - '.json_encode($e->getRequest()).' Response - '.json_encode($e->getResponse()));
            throw new SearchMovieException('Sending request to OMBD failed. Please contact IT.');
        }
    }

    public function getFullMovieInfo(string $imdbID): FullMovieInfoWrapper
    {
        try {
            $client = new Client();

            $response = $client->request('GET', self::OMBD_BASE_URL.'?'.http_build_query([
                'i' => $imdbID,
                'apiKey' => $this->apiKey,
            ]))->getBody()->getContents();

            $response = new FullMovieInfoWrapper(json_decode($response));
            $this->checkErrors($response);

            return $response;
        } catch (RequestException $e) {
            Log::error('OMDB getFullMovieInfo request failed. Request - '.json_encode($e->getRequest()).' Response - '.json_encode($e->getResponse()));
            throw new SearchMovieException('Sending request to OMBD failed. Please contact IT.');
        }
    }

    /**
     * @throws SearchMovieException
     */
    private function checkErrors(Fluent $response): void
    {
        if (! empty($response->Error)) {
            throw new SearchMovieException($response->Error);
        }
    }
}
