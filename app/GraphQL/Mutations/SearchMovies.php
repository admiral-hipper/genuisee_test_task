<?php

declare(strict_types=1);

namespace App\GraphQL\Mutations;

use App\Services\OMDB\OMDBSearcher;

final class SearchMovies
{
    /** @param  array{}  $args */
    public function __invoke(null $_, array $args)
    {
        $ombdSearcher = new OMDBSearcher($args['search'], $args['type'], $args['page']);

        return $ombdSearcher->searchMovie();
    }
}
