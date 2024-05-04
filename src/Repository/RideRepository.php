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
            ->orderBy('r.id', 'DESC')
            ->andWhere('r.date <= :now')
            ->setParameter(':now', new \DateTime());
        if ($userdepartment) {
            $qb->join('r.city', 'c')
                ->andWhere('c.department = :department')
                ->setParameter(':department', $userdepartment);
        }
        if ($limit) {
            $qb->setMaxResults($limit);
        }
        $qb->andWhere('SIZE(r.participants) < r.max_rider');
        return $qb->getQuery()->getResult();
    }

    public function rideOfUser($user)
    {
        $qb = $this->createQueryBuilder('r')
            ->join('r.participants', 'u')
            ->andWhere(':user MEMBER OF r.participants')
            ->andWhere('r.date <= :now')
            ->setParameter(':user', $user)
            ->setParameter(':now', new \DateTime())
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
        $distance_min = null,
        $distance_max = null,
        $participants_min = null,
        $participants_max = null,
        $average_speed_min = null,
        $average_speed_max = null,
        $ascent_min = null,
        $ascent_max = null
    ) {
        $qb = $this->createQueryBuilder('r')->orderBy('r.date', 'ASC');
        $mind ? $qb->andWhere('r.mind = :mind')->setParameter(':mind', $mind) : null;
        $department ? $qb->join('r.city', 'c')->andWhere('c.department = :department')->setParameter(':department', $department) : null;
        $date ? $qb->andWhere('r.date >= :date')->setParameter(':date', $date) : null;
        $distance_min ? $qb->andWhere('r.distance >= :distance_min')->setParameter(':distance_min', $distance_min) : null;
        $distance_max ? $qb->andWhere('r.distance <= :distance_max')->setParameter(':distance_max', $distance_max) : null;
        $participants_min ? $qb->andWhere('r.max_rider >= :participants_min')->setParameter(':participants_min', $participants_min) : null;
        $participants_min ? $qb->andWhere('r.max_rider <= :participants_max')->setParameter(':participants_max', $participants_max) : null;
        $average_speed_min ? $qb->andWhere('r.average_speed >= :average_speed_min')->setParameter(':average_speed_min', $average_speed_min) : null;
        $average_speed_max ? $qb->andWhere('r.average_speed <= :average_speed_max')->setParameter(':average_speed_max', $average_speed_max) : null;
        $ascent_min ? $qb->andWhere('r.ascent >= :ascent_min')->setParameter(':ascent_min', $ascent_min) : null;
        $ascent_max ? $qb->andWhere('r.ascent <= :ascent_max')->setParameter(':ascent_max', $ascent_max) : null;
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
