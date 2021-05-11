<?php

namespace App\Repository;

use App\Entity\Causes;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Causes|null find($id, $lockMode = null, $lockVersion = null)
 * @method Causes|null findOneBy(array $criteria, array $orderBy = null)
 * @method Causes[]    findAll()
 * @method Causes[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CausesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Causes::class);
    }

    // /**
    //  * @return Causes[] Returns an array of Causes objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Causes
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
