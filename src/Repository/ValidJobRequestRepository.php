<?php

namespace App\Repository;

use App\Entity\ValidJobRequest;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ValidJobRequest|null find($id, $lockMode = null, $lockVersion = null)
 * @method ValidJobRequest|null findOneBy(array $criteria, array $orderBy = null)
 * @method ValidJobRequest[]    findAll()
 * @method ValidJobRequest[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ValidJobRequestRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ValidJobRequest::class);
    }

    // /**
    //  * @return ValidJobRequest[] Returns an array of ValidJobRequest objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('v')
            ->andWhere('v.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('v.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?ValidJobRequest
    {
        return $this->createQueryBuilder('v')
            ->andWhere('v.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
