<?php

namespace App\Repository;

use App\Entity\Practice;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Practice>
 *
 * @method Practice|null find($id, $lockMode = null, $lockVersion = null)
 * @method Practice|null findOneBy(array $criteria, array $orderBy = null)
 * @method Practice[]    findAll()
 * @method Practice[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PracticeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Practice::class);
    }

//    /**
//     * @return Practice[] Returns an array of Practice objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('p.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Practice
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
