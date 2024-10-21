<?php
namespace App\Domain\User\Contrat;

interface TokenServiceConfigInterface {
    public function __invoke(array $datas);
}