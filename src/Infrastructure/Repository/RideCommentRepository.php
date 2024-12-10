<?php

namespace App\Infrastructure\Repository;

use App\Domain\Ride\RideComment;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<RideComment>
 *
 * @method RideComment|null find($id, $lockMode = null, $lockVersion = null)
 * @method RideComment|null findOneBy(array $criteria, array $orderBy = null)
 * @method RideComment[]    findAll()
 * @method RideComment[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RideCommentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RideComment::class);
    }

    public function save (RideComment $rideComment): void
    {
        $this->_em->persist($rideComment);
        $this->_em->flush();
    }


    //    /**
    //     * @return RideComment[] Returns an array of RideComment objects
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

    //    public function findOneBySomeField($value): ?RideComment
    //    {
    //        return $this->createQueryBuilder('r')
    //            ->andWhere('r.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
