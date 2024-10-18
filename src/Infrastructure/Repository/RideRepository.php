<?php

namespace App\Infrastructure\Repository;

use App\Domain\Ride\Contrat\RideRepositoryInterface;
use App\Domain\Ride\Ride;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Ride>
 *
 * @method Ride|null find($id, $lockMode = null, $lockVersion = null)
 * @method Ride|null findOneBy(array $criteria, array $orderBy = null)
 * @method Ride[]    findAll()
 * @method Ride[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RideRepository extends ServiceEntityRepository implements RideRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Ride::class);
    }

    public function save(Ride $ride): void
    {
        $this->_em->persist($ride);
        $this->_em->flush();
    }

    public function remove(Ride $ride): void
    {
        $this->_em->remove($ride);
        $this->_em->flush();
    }

    public function myRides($user)
    {
        $qb = $this->createQueryBuilder('r')
            ->join('r.participants', 'u')
            ->andWhere(':user MEMBER OF r.participants')
            ->setParameter(':user', $user)
            ->orderBy('r.startDate', 'ASC')
            ->getQuery();

        return $qb->getResult();
    }

    public function ridePaginated($userdepartment = null)
    {
        $qb = $this->createQueryBuilder('r')
            ->orderBy('r.id', 'DESC');
        if ($userdepartment) {
            $qb->join('r.city', 'c')
                ->andWhere('c.department = :department')
                ->setParameter(':department', $userdepartment);
        }
        $qb->andWhere('SIZE(r.participants) < r.max_rider');

        // ->setParameter(':r.max_rider', 'r.max_rider');
        return $qb->getQuery();
    }

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
    )
    {
        $qb = $this->createQueryBuilder('r')->orderBy('r.startDate', 'ASC');
        $practice ? $qb->andWhere('r.practice = :practice')->setParameter(':practice', $practice) : null;
        $mind ? $qb->andWhere('r.mind = :mind')->setParameter(':mind', $mind) : null;
        $department ? $qb->join('r.startCity', 'c')->andWhere('c.department = :department')->setParameter(':department', $department) : null;
        $startDate ? $qb->andWhere('r.startDate >= :startDate')->setParameter(':startDate', $startDate) : $qb->andWhere('r.startDate >= :startDate')->setParameter(':startDate', new \DateTime());
        $minDistance ? $qb->andWhere('r.distance >= :minDistance')->setParameter(':minDistance', $minDistance) : null;
        $maxDistance ? $qb->andWhere('r.distance <= :maxDistance')->setParameter(':maxDistance', $maxDistance) : null;
        $minParticipants ? $qb->andWhere('r.maxParticipants >= :minParticipants')->setParameter(':minParticipants', $minParticipants) : null;
        $maxParticipants ? $qb->andWhere('r.maxParticipants <= :maxParticipants')->setParameter(':maxParticipants', $maxParticipants) : null;
        $minAverageSpeed ? $qb->andWhere('r.averageSpeed >= :minAverageSpeed')->setParameter(':minAverageSpeed', $minAverageSpeed) : null;
        $maxAverageSpeed ? $qb->andWhere('r.averageSpeed <= :maxAverageSpeed')->setParameter(':maxAverageSpeed', $maxAverageSpeed) : null;
        $minAscent ? $qb->andWhere('r.ascent >= :minAscent')->setParameter(':minAscent', $minAscent) : null;
        $maxAscent ? $qb->andWhere('r.ascent <= :maxAscent')->setParameter(':maxAscent', $maxAscent) : null;

        return $qb->getQuery()->getResult();
    }
    //    /**
    //     * @return Ride[] Returns an array of Ride objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('r')
    //            ->andWhere('r.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('r.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Ride
    //    {
    //        return $this->createQueryBuilder('r')
    //            ->andWhere('r.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
