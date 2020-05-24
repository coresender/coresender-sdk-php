<?php

declare(strict_types=1);

namespace Coresender\Responses\SendEmail;

class SendEmailResponseItem
{
    private $messageId;
    private $customId;
    private $status;
    private $errors = [];

    public function __construct(array $data)
    {
        $this->messageId = $data['message_id'];
        $this->customId = $data['custom_id'];
        $this->status = $data['status'];
        $this->errors = $data['errors'];
    }

    public function getMessageId()
    {
        return $this->messageId;
    }

    public function getCustomId()
    {
        return $this->customId;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function getErrors()
    {
        return $this->errors;
    }
}
