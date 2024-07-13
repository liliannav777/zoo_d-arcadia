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
            $qb->join('r.animal', 'a')
                ->join('a.race', 'rac')
                ->andWhere('rac.label = :race')
                ->setParameter('race', $filterRace);
        }

        $rapportsVeterinaire = $qb->getQuery()->getResult();

        // Création du formulaire pour ajouter un rapport vétérinaire
        $rapportVeterinaire = new RapportVeterinaire();
        $form = $this->createForm(RapportVeterinaireType::class, $rapportVeterinaire, [
            'method' => 'POST',
            'action' => $this->generateUrl('veterinaire'),
        ]);

        // Gestion des données du formulaire pour ajouter un rapport
        if ($form->handleRequest($request)->isSubmitted() && $form->isValid()) {
            $rapportVeterinaire = $form->getData();
            $rapportVeterinaire->setUtilisateur($this->getUser()); 

            $entityManager->persist($rapportVeterinaire);
            $entityManager->flush();

            $this->addFlash('success', 'Le rapport a été ajouté avec succès.');

            return $this->redirectToRoute('veterinaire');
        }

        // Traitement des actions sur les rapports vétérinaires
        if ($request->isMethod('POST')) {
            $rapportId = $request->request->get('rapport_id');
            $action = $request->request->get('action');

            if ($rapportId) {
                $rapport = $rapportVeterinaireRepository->find($rapportId);
                if ($rapport) {
                    if ($action === 'edit') {
                        $editForm = $this->createForm(RapportVeterinaireType::class, $rapport, [
                            'method' => 'POST',
                            'action' => $this->generateUrl('veterinaire', ['rapport_id' => $rapportId]),
                        ]);
                        $editForm->handleRequest($request);

                        if ($editForm->isSubmitted() && $editForm->isValid()) {
                            $entityManager->flush();
                            $this->addFlash('success', 'Rapport modifié avec succès.');

                            return $this->redirectToRoute('veterinaire');
                        }

                        return $this->render('veterinaire/edit.html.twig', [
                            'editForm' => $editForm->createView(),
                        ]);
                    } elseif ($action === 'delete') {
                        $entityManager->remove($rapport);
                        $this->addFlash('success', 'Rapport supprimé avec succès.');
                        $entityManager->flush();
                    }
                }
            }

            return $this->redirectToRoute('veterinaire');
        }

        // Récupération des labels de races d'animaux pour le formulaire de tri
        $races = $entityManager->createQuery('SELECT DISTINCT rac.label FROM App\Entity\Animal a JOIN a.race rac')
            ->getScalarResult();

        // Récupération des rapports des employés
        $rapportsEmploye = $rapportEmployeRepository->findBy(['date' => new DateTime()]);

        return $this->render('veterinaire/dashboard.html.twig', [
            'form' => $form->createView(),
            'rapportsVeterinaire' => $rapportsVeterinaire,
            'rapportsEmploye' => $rapportsEmploye,
            'races' => array_column($races, 'label'),  
        ]);
    }
}
