<?php

namespace App\Domain\User\Contrat;

interface JWTTokenGeneratorServiceInterface
{
    public function __invoke(array $datas): string;
}