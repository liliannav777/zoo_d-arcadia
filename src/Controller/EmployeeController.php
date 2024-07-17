<?php



namespace App\Controller;

use App\Entity\RapportEmploye;
use App\Form\RapportEmployeType;
use App\Repository\AvisRepository;
use App\Repository\AnimalRepository;
use App\Repository\RapportEmployeRepository;
use App\Repository\RapportVeterinaireRepository;
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
        RapportVeterinaireRepository $rapportVeterinaireRepository,
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
    
        $rapportsEmploye = new RapportEmploye();
        $rapportId = $request->request->get('rapport_id');
        
        // Vérifie si l'ID du rapport est spécifié
        if ($rapportId) {
            $rapportEmploye = $rapportEmployeRepository->find($rapportId);
            if (!$rapportEmploye) {
                throw $this->createNotFoundException('Le rapport n\'existe pas.');
            }
        } else {
            // Traite le formulaire de manière classique pour ajouter un rapport
            if ($request->isMethod('POST')) {
                $formData = $request->request->get('rapport_employe');
                $rapportId = $formData['id'] ?? null;
                if ($rapportId) {
                    $rapportEmploye = $rapportEmployeRepository->find($rapportId);
                    if (!$rapportEmploye) {
                        throw $this->createNotFoundException('Le rapport n\'existe pas.');
                    }
                }
            }
        }
        
        // Création du formulaire avec l'entité rapportEmploye
        $form = $this->createForm(RapportEmployeType::class, $rapportEmploye, [
            'method' => 'POST',
            'action' => $this->generateUrl('employee'),
        ]);
        
        $form->handleRequest($request);


        
        if ($form->isSubmitted() && $form->isValid()) {
            $rapportEmploye = $form->getData();
            $rapportEmploye->setEmploye($this->getUser());
        
            if ($rapportEmploye->getId()) {
                // Mise à jour du rapport existant
                $this->addFlash('success', 'Le rapport a été modifié avec succès.');
            } else {
                // Ajout d'un nouveau rapport
                $entityManager->persist($rapportEmploye);
                $this->addFlash('success', 'Le rapport a été ajouté avec succès.');
            }
        
            $entityManager->flush();
            return $this->redirectToRoute('employee');
        }

        // Gestion des données du formulaire pour ajouter ou modifier un rapport
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $rapportEmploye = $form->getData();
            $rapportEmploye->setEmploye($this->getUser()); // Définit l'utilisateur courant comme employé

            if ($rapportEmploye->getId()) {
                // Mise à jour du rapport existant
                $this->addFlash('success', 'Le rapport a été modifié avec succès.');
            } else {
                // Ajout d'un nouveau rapport
                $entityManager->persist($rapportEmploye);
                $this->addFlash('success', 'Le rapport a été ajouté avec succès.');
            }

            $entityManager->flush();
            return $this->redirectToRoute('employee');
        }

        // Traitement des actions sur les avis et les rapports
        if ($request->isMethod('POST')) {
            $avisId = $request->request->get('avis_id');
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
            } elseif ($action === 'delete') {
                $rapport = $rapportEmployeRepository->find($request->request->get('rapport_id'));
                if ($rapport) {
                    $entityManager->remove($rapport);
                    $this->addFlash('success', 'Rapport supprimé avec succès.');
                    $entityManager->flush();
                }
                return $this->redirectToRoute('employee');
            } elseif ($action === 'edit') {
                
            }
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
            'races' => array_column($races, 'label'), 
        ]);
    }
}
