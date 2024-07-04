<?php

namespace App\Repository;

use App\Entity\RapportVétérinaire;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<RapportVétérinaire>
 *
 * @method RapportVétérinaire|null find($id, $lockMode = null, $lockVersion = null)
 * @method RapportVétérinaire|null findOneBy(array $criteria, array $orderBy = null)
 * @method RapportVétérinaire[]    findAll()
 * @method RapportVétérinaire[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RapportVétérinaireRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RapportVétérinaire::class);
    }

//    /**
//     * @return RapportVétérinaire[] Returns an array of RapportVétérinaire objects
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

//    public function findOneBySomeField($value): ?RapportVétérinaire
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
