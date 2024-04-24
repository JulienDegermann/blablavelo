<?php

namespace App\Repository;

use App\Entity\Ride;
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
class RideRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Ride::class);
    }

    public function save(Ride $ride)
    {
        $this->_em->persist($ride);
        $this->_em->flush();
    }

    public function remove(Ride $ride)
    {
        $this->_em->remove($ride);
        $this->_em->flush();
    }


    public function rideList($userdepartment = null, int $limit = null)
    {
        $qb = $this->createQueryBuilder('r')
            ->orderBy('r.id', 'DESC');
        if ($userdepartment) {
            $qb->join('r.city', 'c')
                ->andWhere('c.department = :department')
                ->setParameter(':department', $userdepartment);
        }
        if ($limit) {
            $qb->setMaxResults($limit);
        }
        $qb->andWhere('SIZE(r.participants) < r.max_rider');
        // ->setParameter(':r.max_rider', 'r.max_rider');
        return $qb->getQuery()->getResult();
    }

    public function rideOfUser($user)
    {
        $qb = $this->createQueryBuilder('r')
            ->join('r.participants', 'u')
            ->andWhere(':user MEMBER OF r.participants')
            ->setParameter(':user', $user)
            ->orderBy('r.date', 'ASC')
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
        $mind = null,
        $department = null,
        $date = null,
        $distance = null,
        $participants = null,
        $average_speed = null,
        $ascent = null
    ) {
        $qb = $this->createQueryBuilder('r')->orderBy('r.date', 'ASC');
        $mind ? $qb->andWhere('r.mind = :mind')->setParameter(':mind', $mind) : null;
        $department ? $qb->join('r.city', 'c')->andWhere('c.department = :department')->setParameter(':department', $department) : null;
        $date ? $qb->andWhere('r.date = :date')->setParameter(':date', $date) : null;
        $distance ? $qb->andWhere('r.distance = :distance')->setParameter(':distance', $distance) : null;
        $participants ? $qb->andWhere('r.max_rider = :participants')->setParameter(':participants', $participants) : null;
        $average_speed ? $qb->andWhere('r.average_speed = :average_speed')->setParameter(':average_speed', $average_speed) : null;
        $ascent ? $qb->andWhere('r.ascent = :ascent')->setParameter(':ascent', $ascent) : null;
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
