<?php

namespace App\Repository;

use App\Entity\UserLoggin;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method UserLoggin|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserLoggin|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserLoggin[]    findAll()
 * @method UserLoggin[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserLogginRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserLoggin::class);
    }

    // /**
    //  * @return UserLoggin[] Returns an array of UserLoggin objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('u.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?UserLoggin
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
