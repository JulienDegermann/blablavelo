<?php

namespace App\Domain\User\Contrat;

interface JWTTokenDecodeInterface
{
    public function __invoke(string $token): array;
}