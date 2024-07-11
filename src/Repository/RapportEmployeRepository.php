<?php

namespace App\Repository;

use App\Entity\RapportEmploye;
use App\Entity\RapportEmployé;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<RapportEmployé>
 *
 * @method RapportEmployé|null find($id, $lockMode = null, $lockVersion = null)
 * @method RapportEmployé|null findOneBy(array $criteria, array $orderBy = null)
 * @method RapportEmployé[]    findAll()
 * @method RapportEmployé[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RapportEmployeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RapportEmploye::class);
    }

//    /**
//     * @return RapportEmployé[] Returns an array of RapportEmployé objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('r.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?RapportEmployé
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
