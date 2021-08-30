<?php

namespace App\Repository;

use App\Entity\TreatmentType;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method TreatmentType|null find($id, $lockMode = null, $lockVersion = null)
 * @method TreatmentType|null findOneBy(array $criteria, array $orderBy = null)
 * @method TreatmentType[]    findAll()
 * @method TreatmentType[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TreatmentTypeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TreatmentType::class);
    }

    // /**
    //  * @return TreatmentType[] Returns an array of TreatmentType objects
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

    /*
    public function findOneBySomeField($value): ?TreatmentType
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
