<?php

declare(strict_types=1);

namespace Coresender\Responses\SendEmail;

use Coresender\Responses\ApiResponseInterface;
use IteratorAggregate;
use ArrayIterator;
use Traversable;

class SendEmailResponse implements ApiResponseInterface, IteratorAggregate
{
    /** @var SendEmailResponseItem[] */
    private $items = [];

    /** @var array */
    private $meta = [];

    /** @var int */
    private $httpStatus;

    public function __construct(array $items, array $meta, int $httpStatus)
    {
        $this->items = $items;
        $this->meta = $meta;
        $this->httpStatus = $httpStatus;
    }

    public static function create(array $data, int $httpStatus)
    {
        $items = array_map(function ($item) {
            return new SendEmailResponseItem($item);
        }, $data['data']);

        return new self($items, $data['meta'], $httpStatus);
    }

    public function allAccepted(): bool
    {
        return $this->httpStatus === 200;
    }

    /**
     * @return SendEmailResponseItem[]
     */
    public function getItems(): array
    {
        return $this->items;
    }

    public function getMeta(): array
    {
        return $this->meta;
    }

    /**
     * @return ArrayIterator|Traversable|SendEmailResponseItem[]
     */
    public function getIterator()
    {
        return new ArrayIterator($this->items);
    }
}
