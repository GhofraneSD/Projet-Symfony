<?php

namespace App\Repository;

use App\Entity\Talk;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use function Doctrine\ORM\QueryBuilder;

/**
 * @method Talk|null find($id, $lockMode = null, $lockVersion = null)
 * @method Talk|null findOneBy(array $criteria, array $orderBy = null)
 * @method Talk[]    findAll()
 * @method Talk[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TalkRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Talk::class);
    }

    // /**
    //  * @return Talk[] Returns an array of Talk objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('t.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */


    public function findDiscussion($user1, $user2)
    {
        return $this->createQueryBuilder('t')
            ->where( 't.receiver = :user1')
            ->orWhere('t.sender = :user1' )
            ->orWhere('t.receiver = :user2' )
            ->orWhere('t.sender = :user2' )
            ->setParameter('user1', $user1)
            ->setParameter('user2', $user2)
            ->orderBy('t.sendDate', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }

}
