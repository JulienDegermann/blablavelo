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


    public function rideList($userdepartment = null, int $limit = null) {
        $qb = $this->createQueryBuilder('r')
            ->orderBy('r.id', 'DESC');
        if($userdepartment) {
            $qb->join('r.city', 'c')
                ->andWhere('c.department = :department')
                ->setParameter(':department', $userdepartment);
        }
        if($limit) {
            $qb->setMaxResults($limit);
        }
        return $qb->getQuery()->getResult();
    }


    public function ridePaginated($userdepartment = null) {
        $qb = $this->createQueryBuilder('r')
            ->orderBy('r.id', 'DESC');
        if($userdepartment) {
        $qb->join('r.city', 'c')
            ->andWhere('c.department = :department')
            ->setParameter(':department', $userdepartment);
        }
        return $qb->getQuery();
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
