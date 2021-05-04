<?php

namespace App\Repository;

use App\Entity\MatosCatego;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method MatosCatego|null find($id, $lockMode = null, $lockVersion = null)
 * @method MatosCatego|null findOneBy(array $criteria, array $orderBy = null)
 * @method MatosCatego[]    findAll()
 * @method MatosCatego[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MatosCategoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MatosCatego::class);
    }

    // /**
    //  * @return MatosCatego[] Returns an array of MatosCatego objects
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
    public function findOneBySomeField($value): ?MatosCatego
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
