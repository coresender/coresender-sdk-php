<?php

declare(strict_types=1);

namespace Coresender\Responses\SendingApi;

use Coresender\Responses\ApiResponseInterface;

class SendEmailApiResponse implements ApiResponseInterface
{
    /** @var SendEmailResponseItem[] */
    private $items;

    /** @var array */
    private $meta;

    public function __construct(array $items, array $meta)
    {
        $this->items = $items;
        $this->meta = $meta;
    }

    public static function create(array $data)
    {
        $items = array_map(function ($item) {
            return new SendEmailResponseItem($item);
        }, $data['data']);

        return new self($items, $data['meta']);
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
}
