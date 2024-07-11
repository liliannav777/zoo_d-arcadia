<?php

// src/Controller/EmployeeController.php

namespace App\Controller;

use App\Entity\RapportEmploye;
use App\Form\RapportEmployeType;
use App\Repository\AvisRepository;
use App\Repository\AnimalRepository;
use App\Repository\RapportEmployeRepository;
use App\Repository\RapportVeterinaireRepository;  // Ajout du repository pour les rapports vétérinaires
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class EmployeeController extends AbstractController
{
    #[Route('/employee', name: 'employee')]
    public function index(
        Request $request,
        AvisRepository $avisRepository,
        RapportEmployeRepository $rapportEmployeRepository,
        RapportVeterinaireRepository $rapportVeterinaireRepository,  // Injection du repository pour les rapports vétérinaires
        EntityManagerInterface $entityManager,
        AnimalRepository $animalRepository
    ): Response {
        // Récupération des avis non validés
        $avisNonValides = $avisRepository->findUnvalidatedAvis();

        // Récupération des paramètres de filtrage
        $filterDate = $request->query->get('date');
        $filterRace = $request->query->get('race');

        // Création de la requête pour les rapports d'employé
        $qb = $rapportEmployeRepository->createQueryBuilder('r')
            ->where('r.employe = :employe')
            ->setParameter('employe', $this->getUser());

        // Date du jour
        $today = new DateTime();
        $startOfDay = $today->setTime(0, 0);
        $endOfDay = (clone $today)->setTime(23, 59, 59);

        // Si une date est spécifiée dans le filtre, ajuster la période de temps
        if ($filterDate) {
            $date = new DateTime($filterDate);
            $startOfDay = $date->setTime(0, 0);
            $endOfDay = (clone $date)->setTime(23, 59, 59);
        }

        // Filtrer par date
        $qb->andWhere('r.date BETWEEN :start AND :end')
            ->setParameter('start', $startOfDay)
            ->setParameter('end', $endOfDay);

        // Filtrer par race si spécifiée
        if ($filterRace) {
            $qb->join('r.animal', 'a')
                ->join('a.race', 'rac')
                ->andWhere('rac.label = :race')
                ->setParameter('race', $filterRace);
        }

        $rapportsEmploye = $qb->getQuery()->getResult();

        // Création du formulaire pour ajouter un rapport d'employé
        $rapportEmploye = new RapportEmploye();
        $form = $this->createForm(RapportEmployeType::class, $rapportEmploye, [
            'method' => 'POST',
            'action' => $this->generateUrl('employee'),
        ]);

        // Gestion des données du formulaire pour ajouter un rapport
        if ($form->handleRequest($request)->isSubmitted() && $form->isValid()) {
            $rapportEmploye = $form->getData();
            $rapportEmploye->setEmploye($this->getUser()); // Définit l'utilisateur courant comme employé

            $entityManager->persist($rapportEmploye);
            $entityManager->flush();

            $this->addFlash('success', 'Le rapport a été ajouté avec succès.');

            return $this->redirectToRoute('employee');
        }

        // Traitement des actions sur les avis et les rapports
        if ($request->isMethod('POST')) {
            $avisId = $request->request->get('avis_id');
            $rapportId = $request->request->get('rapport_id');
            $action = $request->request->get('action');

            if ($avisId) {
                $avis = $avisRepository->find($avisId);
                if ($avis) {
                    if ($action === 'validate') {
                        $avis->setVisible(true);
                        $this->addFlash('success', 'Avis validé avec succès.');
                    } elseif ($action === 'delete') {
                        $entityManager->remove($avis);
                        $this->addFlash('success', 'Avis supprimé avec succès.');
                    }
                    $entityManager->flush();
                }
            } elseif ($rapportId) {
                $rapport = $rapportEmployeRepository->find($rapportId);
                if ($rapport) {
                    if ($action === 'edit') {
                        $editForm = $this->createForm(RapportEmployeType::class, $rapport, [
                            'method' => 'POST',
                            'action' => $this->generateUrl('employee', ['rapport_id' => $rapportId]),
                        ]);
                        $editForm->handleRequest($request);

                        if ($editForm->isSubmitted() && $editForm->isValid()) {
                            $entityManager->flush();
                            $this->addFlash('success', 'Rapport modifié avec succès.');

                            return $this->redirectToRoute('employee');
                        }

                        return $this->render('employee/edit.html.twig', [
                            'editForm' => $editForm->createView(),
                        ]);
                    } elseif ($action === 'delete') {
                        $entityManager->remove($rapport);
                        $this->addFlash('success', 'Rapport supprimé avec succès.');
                        $entityManager->flush();
                    }
                }
            }

            return $this->redirectToRoute('employee');
        }

        // Récupération des rapports vétérinaires
        $qbVet = $rapportVeterinaireRepository->createQueryBuilder('r')
            ->join('r.animal', 'a')
            ->join('a.race', 'rac')
            ->where('r.date BETWEEN :start AND :end')
            ->setParameter('start', $startOfDay)
            ->setParameter('end', $endOfDay);

        // Filtrer par date
        if ($filterDate) {
            $date = new DateTime($filterDate);
            $startOfDay = $date->setTime(0, 0);
            $endOfDay = (clone $date)->setTime(23, 59, 59);

            $qbVet->andWhere('r.date BETWEEN :start AND :end')
                ->setParameter('start', $startOfDay)
                ->setParameter('end', $endOfDay);
        }

        // Filtrer par race si spécifiée
        if ($filterRace) {
            $qbVet->andWhere('rac.label = :race')
                ->setParameter('race', $filterRace);
        }

        $rapportsVeterinaire = $qbVet->getQuery()->getResult();

        // Récupération des labels de races d'animaux pour le formulaire de tri
        $races = $entityManager->createQuery('SELECT DISTINCT rac.label FROM App\Entity\Animal a JOIN a.race rac')
            ->getScalarResult();

        return $this->render('employee/dashboard.html.twig', [
            'form' => $form->createView(),
            'avisNonValides' => $avisNonValides,
            'rapportsEmploye' => $rapportsEmploye,
            'rapportsVeterinaire' => $rapportsVeterinaire,
            'races' => array_column($races, 'label'),  // 'label' est le champ retourné par la requête DQL
        ]);
    }
}
