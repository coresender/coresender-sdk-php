<?php

declare(strict_types=1);

namespace Coresender\Exception;

use RuntimeException;
use Throwable;

class ApiException extends RuntimeException implements CoresenderException
{
    protected $errorCode;
    protected $errors;

    public function __construct(string $errorCode, array $errors, $message = "", $code = 0, Throwable $previous = null)
    {
        $this->errorCode = $errorCode;
        $this->errors = $errors;

        parent::__construct($message, $code, $previous);
    }

    public function getErrorCode(): string
    {
        return $this->errorCode;
    }

    public function getErrors(): array
    {
        return $this->errors;
    }
}
