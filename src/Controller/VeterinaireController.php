<?php

namespace App\Controller;

use App\Entity\RapportVeterinaire;
use App\Form\RapportVeterinaireType;
use App\Repository\AnimalRepository;
use App\Repository\RapportEmployeRepository;
use App\Repository\RapportVeterinaireRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class VeterinaireController extends AbstractController
{
    #[Route('/veterinaire', name: 'veterinaire')]
    public function index(
        Request $request,
        RapportVeterinaireRepository $rapportVeterinaireRepository,
        RapportEmployeRepository $rapportEmployeRepository,
        EntityManagerInterface $entityManager,
        AnimalRepository $animalRepository
    ): Response {
        // Récupération des paramètres de filtrage
        $filterDate = $request->query->get('date');
        $filterRace = $request->query->get('race');
    
        // Création de la requête pour les rapports vétérinaires
        $qb = $rapportVeterinaireRepository->createQueryBuilder('r')
            ->leftJoin('r.animal', 'a')
            ->leftJoin('a.race', 'rac')
            ->where('r.date BETWEEN :start AND :end')
            ->setParameter('start', (new DateTime())->setTime(0, 0))
            ->setParameter('end', (new DateTime())->setTime(23, 59, 59));
    
        // Si une date est spécifiée dans le filtre, ajuster la période de temps
        if ($filterDate) {
            $date = new DateTime($filterDate);
            $startOfDay = $date->setTime(0, 0);
            $endOfDay = (clone $date)->setTime(23, 59, 59);
    
            $qb->andWhere('r.date BETWEEN :start AND :end')
                ->setParameter('start', $startOfDay)
                ->setParameter('end', $endOfDay);
        }
    
        // Filtrer par race si spécifiée
        if ($filterRace) {
            $qb->andWhere('rac.label = :race')
                ->setParameter('race', $filterRace);
        }
    
        $rapportsVeterinaire = $qb->getQuery()->getResult();
    
        // Création d'un rapport vétérinaire vide pour le formulaire d'ajout ou modification
        $rapportVeterinaire = new RapportVeterinaire();
    
        // Si un ID de rapport est spécifié dans la requête, on charge le rapport pour modification
        $rapportId = $request->query->get('rapport_id');
        if ($rapportId) {
            $rapportVeterinaire = $rapportVeterinaireRepository->find($rapportId);
            if (!$rapportVeterinaire) {
                throw $this->createNotFoundException('Rapport vétérinaire non trouvé.');
            }
        }
    
        $form = $this->createForm(RapportVeterinaireType::class, $rapportVeterinaire, [
            'method' => 'POST',
            'action' => $this->generateUrl('veterinaire', ['rapport_id' => $rapportId]),
        ]);
        
        $form->handleRequest($request);
        
        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $rapportVeterinaire = $form->getData();
                $rapportVeterinaire->setUtilisateur($this->getUser());
    
                // Si un ID de rapport est spécifié, on est en mode modification
                if ($rapportId) {
                    // Vérifiez que le rapport existe avant de faire un flush
                    $existingRapport = $rapportVeterinaireRepository->find($rapportId);
                    if ($existingRapport) {
                        $entityManager->flush();
                        $this->addFlash('success', 'Le rapport a été modifié avec succès.');
                    } else {
                        $this->addFlash('error', 'Le rapport à modifier n\'existe pas.');
                    }
                } else {
                    // Vérifiez que le rapport n'existe pas déjà avec ces données
                    $existingRapport = $rapportVeterinaireRepository->findOneBy([
                        'animal' => $rapportVeterinaire->getAnimal(),
                        'date' => $rapportVeterinaire->getDate()
                    ]);
                    if ($existingRapport) {
                        $this->addFlash('error', 'Un rapport vétérinaire existe déjà pour cet animal à cette date.');
                    } else {
                        $entityManager->persist($rapportVeterinaire);
                        $entityManager->flush();
                        $this->addFlash('success', 'Le rapport a été ajouté avec succès.');
                    }
                }
    
                return $this->redirectToRoute('veterinaire');
            } else {
                $this->addFlash('error', 'Le formulaire est invalide.');
            }
        }
    
        // Gestion de la suppression d'un rapport vétérinaire
        if ($request->isMethod('POST') && $request->request->get('action') === 'delete') {
            $rapportId = $request->request->get('rapport_id');
            $rapportVeterinaire = $rapportVeterinaireRepository->find($rapportId);
            if ($rapportVeterinaire) {
                $entityManager->remove($rapportVeterinaire);
                $entityManager->flush();
                $this->addFlash('success', 'Le rapport a été supprimé avec succès.');
            } else {
                $this->addFlash('error', 'Le rapport n\'existe pas.');
            }
    
            return $this->redirectToRoute('veterinaire');
        }
    
        // Récupération des labels de races d'animaux pour le formulaire de tri
        $races = $entityManager->createQuery('SELECT DISTINCT rac.label FROM App\Entity\Animal a JOIN a.race rac')
            ->getScalarResult();
    
        // Récupération des rapports des employés en fonction de la date filtrée
        $rapportEmployeQuery = $rapportEmployeRepository->createQueryBuilder('r')
            ->where('r.date BETWEEN :start AND :end')
            ->setParameter('start', $filterDate ? (new DateTime($filterDate))->setTime(0, 0) : (new DateTime())->setTime(0, 0))
            ->setParameter('end', $filterDate ? (clone (new DateTime($filterDate)))->setTime(23, 59, 59) : (new DateTime())->setTime(23, 59, 59));
    
        $rapportsEmploye = $rapportEmployeQuery->getQuery()->getResult();
    
        return $this->render('veterinaire/dashboard.html.twig', [
            'form' => $form->createView(),
            'rapportsVeterinaire' => $rapportsVeterinaire,
            'rapportsEmploye' => $rapportsEmploye,
            'races' => array_column($races, 'label'),
        ]);
    }
}