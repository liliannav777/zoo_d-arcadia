<?php

namespace App\Repository;

use App\Entity\Animal;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class AnimalRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Animal::class);
    }

    /**
     * Trouver les animaux par l'identifiant de l'habitat.
     *
     * @param int $habitatId
     * @return Animal[]
     */
    public function findByHabitatId(int $habitatId)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.habitat = :habitatId')
            ->setParameter('habitatId', $habitatId)
            ->getQuery()
            ->getResult();
    }
}
