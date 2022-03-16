<?php

namespace App\Repository;

use App\Entity\Serializer;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Serializer|null find($id, $lockMode = null, $lockVersion = null)
 * @method Serializer|null findOneBy(array $criteria, array $orderBy = null)
 * @method Serializer[]    findAll()
 * @method Serializer[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SerializerRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Serializer::class);
    }

    // /**
    //  * @return Serializer[] Returns an array of Serializer objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Serializer
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
