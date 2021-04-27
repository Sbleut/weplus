<?php

namespace App\Repository;

use App\Entity\Matos;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Matos|null find($id, $lockMode = null, $lockVersion = null)
 * @method Matos|null findOneBy(array $criteria, array $orderBy = null)
 * @method Matos[]    findAll()
 * @method Matos[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MatosRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Matos::class);
    }

    // /**
    //  * @return Matos[] Returns an array of Matos objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('m.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Matos
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
