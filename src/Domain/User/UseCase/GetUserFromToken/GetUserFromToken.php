<?php

namespace App\Domain\User\UseCase\GetUserFromToken;

use App\Domain\User\Contrat\GetUserFromTokenInterface;
use App\Domain\User\Contrat\JWTTokenDecodeInterface;
use App\Domain\User\User;
use App\Domain\User\Contrat\UserRepositoryInterface;
use InvalidArgumentException;

final class GetUserFromToken implements GetUserFromTokenInterface
{
    public function __construct(
        private readonly JWTTokenDecodeInterface $JWTDecoder,
        private readonly UserRepositoryInterface $userRepo,
    ) {}

    public function __invoke(string $token): User
    {
        $userId = ($this->JWTDecoder)($token);

        $user = $this->userRepo->find($userId['user_id']);
        if (!$user || !($user instanceof User)) {
            throw new InvalidArgumentException('Utilisateur introuvable');
        }

        return $user;
    }
}
