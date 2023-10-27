<?php

namespace App\Repository;

use App\Entity\Mind;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Mind>
 *
 * @method Mind|null find($id, $lockMode = null, $lockVersion = null)
 * @method Mind|null findOneBy(array $criteria, array $orderBy = null)
 * @method Mind[]    findAll()
 * @method Mind[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MindRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Mind::class);
    }

//    /**
//     * @return Mind[] Returns an array of Mind objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('m')
//            ->andWhere('m.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('m.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Mind
//    {
//        return $this->createQueryBuilder('m')
//            ->andWhere('m.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
