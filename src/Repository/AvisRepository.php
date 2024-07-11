<?php

// src/Repository/AvisRepository.php
namespace App\Repository;

use App\Entity\Avis;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class AvisRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Avis::class);
    }

    /**
     * Récupère les avis non validés
     */
    public function findUnvalidatedAvis()
    {
        return $this->createQueryBuilder('a')
            ->where('a.isVisible = :isVisible')
            ->setParameter('isVisible', false)
            ->getQuery()
            ->getResult();
    }
}
