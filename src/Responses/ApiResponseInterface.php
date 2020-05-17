<?php

declare(strict_types=1);

namespace Coresender\Responses;

interface ApiResponseInterface
{
    public static function create(array $data);
}
