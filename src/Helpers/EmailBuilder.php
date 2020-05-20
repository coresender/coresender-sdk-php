<?php

namespace Coresender\Helpers;

class EmailBuilder
{
    private $data = [];

    public function setFrom(string $email, ?string $name = null)
    {
        $this->data['from'] = ['email' => $email, 'name' => $name];
        return $this;
    }

    public function addToRecipient(string $email, ?string $name = null)
    {
        $this->data['to'][] = ['email' => $email, 'name' => $name];
        return $this;
    }

    public function setSubject(string $subject)
    {
        $this->data['subject'] = $subject;
        return $this;
    }

    public function setBodyHtml(string $body): self
    {
        $this->data['body']['html'] = $body;
        return $this;
    }

    public function setBodyText(string $body): self
    {
        $this->data['body']['text'] = $body;
        return $this;
    }

    public function setCustomId(string $customId): self
    {
        $this->data['custom_id'] = $customId;
        return $this;
    }

    public function setCustomIdUnique(bool $customIdUnique): self
    {
        $this->data['custom_id_unique'] = $customIdUnique;
        return $this;
    }

    public function setTrackOpens(bool $trackOpens): self
    {
        $this->data['track_opens'] = $trackOpens;
        return $this;
    }

    public function setTrackClicks(bool $trackClicks): self
    {
        $this->data['track_clicks'] = $trackClicks;
        return $this;
    }

    public function setListId(string $listId): self
    {
        $this->data['list_id'] = $listId;
        return $this;
    }

    public function setListUnsubscribe(string $listUnsubscribe): self
    {
        $this->data['list_unsubscribe'] = $listUnsubscribe;
        return $this;
    }

    public function getEmail(): array
    {
        return $this->data;
    }
}
