<?php

namespace App\Repository;

use App\Entity\PendingJobRequest;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method PendingJobRequest|null find($id, $lockMode = null, $lockVersion = null)
 * @method PendingJobRequest|null findOneBy(array $criteria, array $orderBy = null)
 * @method PendingJobRequest[]    findAll()
 * @method PendingJobRequest[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PendingJobRequestRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PendingJobRequest::class);
    }

    // /**
    //  * @return PendingJobRequest[] Returns an array of PendingJobRequest objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?PendingJobRequest
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
