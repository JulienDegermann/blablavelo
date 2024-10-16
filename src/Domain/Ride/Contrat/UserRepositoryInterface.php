<?php
namespace App\Domain\Ride\Contrat;
interface UserRepositoryInterface{
    public function save(Ride $ride): void;
}