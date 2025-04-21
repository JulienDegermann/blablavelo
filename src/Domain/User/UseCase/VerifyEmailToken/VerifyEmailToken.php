<?php

namespace App\Domain\User\UseCase\VerifyEmailToken;

use App\Domain\User\Contrat\JWTTokenDecodeInterface;
use App\Domain\User\Contrat\UserRepositoryInterface;
use App\Domain\User\Contrat\VerifyEmailTokenInterface;
use Monolog\DateTimeImmutable;

final class VerifyEmailToken implements VerifyEmailTokenInterface
{
    public function __construct(
        private readonly JWTTokenDecodeInterface $decodeJWTToken,
        private readonly UserRepositoryInterface $userRepo,
    )
    {
    }

    public function __invoke(string $token): bool
    {
        // decode the token
        $decodedPayload = ($this->decodeJWTToken)($token);

        // le decode =  explode dans un array puis 2e part : donne le payload
        $now = (new DateTimeImmutable('now'))->getTimestamp();

        if ($decodedPayload['iat'] < $now && $now < $decodedPayload['exp']) {
            $user = $this->userRepo->find($decodedPayload['user_id']);
            $user->setIsVerified(true);
            $this->userRepo->save($user);

            return true;
        }

        return false;
    }
}
