<?php

namespace App\Exceptions;

use Exception;
use GraphQL\Error\ClientAware;

class UpdateMovieException extends Exception implements ClientAware
{
    public function isClientSafe(): bool
    {
        return true;
    }

    public function getCategory()
    {
        return 'update-movie';
    }
}
