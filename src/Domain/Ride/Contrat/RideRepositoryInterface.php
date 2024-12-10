<?php

namespace App\Domain\Ride\Contrat;

use App\Domain\Location\Department;
use App\Domain\PracticeDetail\Mind;
use App\Domain\PracticeDetail\Practice;
use App\Domain\Ride\Ride;
use App\Domain\User\User;
use DateTimeImmutable;

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
     * @return ?array
     */
    public function findAll();

    public function myRides(User $user);

    /**
     * method to find multiple rides matching criteria
     * @param array $criteria - criteria that rides should match
     * @return ?Ride[]
     */
    public function findBy(array $criteria);

    /**
     * method to find first ride matching criteria
     * @param array $criteria - criteria that rides should match
     * @return Ride
     */
    public function findOneBy(array $criteria);

    /**
     * method to find a Ride by its primary key (id)
     * @param int $id - ride's id
     * @return Ride
     */
    public function find(mixed $id);

    /**
     * method to find a Ride by its primary key (id)
     * @param Practice $practice - ride Practice filter
     * @param Mind $mind - ride Mind filter
     * @param Department $department - ride Department filter
     * @param DateTimeImmutable $startDate - ride start date and after
     * @param int $minDistance - min distance for Ride
     * @param int $maxDistance - max distance for Ride
     * @param int $minParticipants - min participants for Ride
     * @param int $maxParticipants - max participants for Ride
     * @param int $minAverageSpeed - min participants for Ride
     * @param int $maxAverageSpeed - max participants for Ride
     * @param int $minAscent - min ascent for Ride
     * @param int $maxAscent - max ascent for Ride
     * @return Ride[] - array of rides
     */
    public function rideFilter(
        $practice = null,
        $mind = null,
        $department = null,
        $startDate = null,
        $minDistance = null,
        $maxDistance = null,
        $minParticipants = null,
        $maxParticipants = null,
        $minAverageSpeed = null,
        $maxAverageSpeed = null,
        $minAscent = null,
        $maxAscent = null
    );
}