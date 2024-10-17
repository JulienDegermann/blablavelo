<?php

namespace App\Domain\Ride\Contrat;

use App\Domain\Ride\Ride;

interface RideRepositoryInterface
{
    /**
     *  method to save a new ride
     * @return void
     */
    public function save(Ride $ride): void;

    /**
     * method to remove a ride with its id
     * @param Ride $ride - Ride to remove
     * @return void
     */
    public function remove(Ride $ride): void;

    /**
     * method to find all rides saved in database
     * @return array
     */
    public function findAll(): ?array;

    /**
     * method to find multiple rides matching criteria
     * @param array $criteria - criteria that rides should match
     * @return Ride[]
     */
    public function findBy(array $criteria): ?array;

    /**
     * method to find first ride matching criteria
     * @param array $criteria - criteria that rides should match
     * @return Ride
     */
    public function findOneBy(array $criteria): ?Ride;

    /**
     * method to find a Ride by its primary key (id)
     * @param int $id - ride's id
     * @return Ride
     */
    public function find(int $id): ?Ride;
}