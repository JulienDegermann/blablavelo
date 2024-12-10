<?php

namespace App\Infrastructure\Service\JWTTokenGeneratorService;

use App\Domain\User\Contrat\JWTTokenGeneratorServiceInterface;
use DateTimeImmutable;

final class JWTTokenGeneratorService implements JWTTokenGeneratorServiceInterface
{
    public function __construct(
        private readonly string $secret,
    )
    {
    }

    private function base64UrlEncode($data)
    {
        return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
    }

    public function __invoke(array $datas): string
    {
        $header = json_encode([
            "alg" => "HS256",
            "typ" => "JWT",
        ]);

        // initialize and expire dates
        $payload = [
            'iat' => (new DateTimeImmutable())->getTimestamp(),
            'exp' => (new DateTimeImmutable("now + 1 hour"))->getTimestamp(),
        ];
        // include datas
        $payload = array_merge($payload, $datas);

        $payload = json_encode($payload);

        $headerEncoded = $this->base64UrlEncode($header);
        $payloadEncoded = $this->base64UrlEncode($payload);

        $signature = hash_hmac('sha256', $headerEncoded . '.' . $payloadEncoded, $this->secret, true);
        $signatureEncoded = $this->base64UrlEncode($signature);

        $jwt = "$headerEncoded.$payloadEncoded.$signatureEncoded";

        return $jwt;
    }
}