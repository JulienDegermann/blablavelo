<?php

namespace App\Domain\Message\UseCase\SendMessage;

use App\Domain\Message\Message;
use App\Domain\User\User;

final class SendMessageInput
{
    private User $user;

    private string $title;

    private string $text;

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function setUser(User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getText(): string
    {
        return $this->text;
    }

    public function setText(string $text): self
    {
        $this->text = $text;

        return $this;
    }

    public function __construct(User $user)
    {
        $this->user = $user;
    }
}