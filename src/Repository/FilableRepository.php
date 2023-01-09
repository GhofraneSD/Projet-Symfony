<?php

namespace App\Repository;

use App\Entity\Filable;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Filable|null find($id, $lockMode = null, $lockVersion = null)
 * @method Filable|null findOneBy(array $criteria, array $orderBy = null)
 * @method Filable[]    findAll()
 * @method Filable[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FilableRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Filable::class);
    }

    // /**
    //  * @return Filable[] Returns an array of Filable objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('f.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Filable
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
