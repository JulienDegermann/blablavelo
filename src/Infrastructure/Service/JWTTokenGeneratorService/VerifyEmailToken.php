<?php

namespace App\Infrastructure\Service\JWTTokenGeneratorService;

use App\Domain\User\Contrat\JWTTokenDecodeInterface;

final class VerifyEmailToken implements JWTTokenDecodeInterface
{
    public function __invoke(string $token): array
    {
        $payload = json_decode(base64_decode(explode('.', $token)[1]), true);

        return $payload;
    }
}
