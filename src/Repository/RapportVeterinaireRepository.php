<?php

namespace App\Repository;

use App\Entity\RapportVeterinaire;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class RapportVeterinaireRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RapportVeterinaire::class);
    }

    public function findByFilters($animal, $date)
    {
        $queryBuilder = $this->createQueryBuilder('r')
            ->join('r.animal', 'a')
            ->select('r, a');

        if ($animal) {
            $queryBuilder->andWhere('a.id = :animal')  
                ->setParameter('animal', $animal);
        }

        if ($date) {
            $queryBuilder->andWhere('DATE(r.date) = :date')  
                ->setParameter('date', $date);
        }

        return $queryBuilder->getQuery()->getResult();
    }
    public function countConsultationsByAnimal()
    {
        return $this->createQueryBuilder('r')
            ->select('a.prenom as animalName, COUNT(r.rapport_veterinaire_id) as consultations')
            ->join('r.animal', 'a')
            ->groupBy('a.animal_id')
            ->getQuery()
            ->getArrayResult();
    }
}
