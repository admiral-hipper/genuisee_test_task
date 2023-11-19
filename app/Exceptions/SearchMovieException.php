<?php

namespace App\Exceptions;

use Exception;
use GraphQL\Error\ClientAware;

class SearchMovieException extends Exception implements ClientAware
{
    public function isClientSafe(): bool
    {
        return true;
    }

    public function getCategory()
    {
        return 'search-movie';
    }
}
