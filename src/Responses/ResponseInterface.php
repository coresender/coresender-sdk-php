<?php

declare(strict_types=1);

namespace Coresender\Responses;

interface ResponseInterface
{
    public static function create(array $data, array $meta);
}
