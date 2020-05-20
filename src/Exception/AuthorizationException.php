<?php

declare(strict_types=1);

namespace Coresender\Exception;

use RuntimeException;

class AuthorizationException extends RuntimeException implements CoresenderException
{
}
