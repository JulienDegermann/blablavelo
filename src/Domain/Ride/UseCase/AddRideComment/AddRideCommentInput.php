<?php

namespace App\Domain\Ride\UseCase\AddRideComment;

use App\Domain\Ride\Ride;
use App\Domain\User\User;

final class AddRideCommentInput
{
    private Ride $ride;

    private User $author;

    private string $text;

    public function getRide(): Ride
    {
        return $this->ride;
    }

    public function setRide(Ride $ride): self
    {
        $this->ride = $ride;

        return $this;
    }

    public function getAuthor(): User
    {
        return $this->author;
    }

    public function setAuthor(User $author): self
    {
        $this->author = $author;

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

    public function __construct(
        Ride   $ride,
        User   $author
    )
    {
        $this->ride = $ride;
        $this->author = $author;
    }
}
