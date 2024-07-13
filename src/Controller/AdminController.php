<?php

namespace App\Controller;

use App\Entity\Utilisateur;
use App\Form\UserCreationFormType;
use App\Repository\AnimalRepository;
use App\Repository\RapportVeterinaireRepository;
use App\Repository\ServiceRepository;
use App\Service\ClickService;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AdminController extends AbstractController
{
    #[Route('/admin', name: 'admin')]
    #[IsGranted('ROLE_ADMIN')]
    public function adminDashboard(
        Request $request,
        EntityManagerInterface $entityManager,
        UserPasswordHasherInterface $passwordHasher,
        RapportVeterinaireRepository $reportRepository,
        ServiceRepository $serviceRepository,
        AnimalRepository $animalRepository,
        ClickService $clickService
    ): Response {
        // Formulaire de création d'utilisateur
        $user = new Utilisateur();
        $userForm = $this->createForm(UserCreationFormType::class, $user, [
            'entityManager' => $entityManager,
        ]);
        $userForm->handleRequest($request);

        if ($userForm->isSubmitted() && $userForm->isValid()) {
            $user->setPassword(
                $passwordHasher->hashPassword($user, $user->getPassword())
            );
            $entityManager->persist($user);
            $entityManager->flush();

            $this->addFlash('success', 'Utilisateur créé avec succès');
            return $this->redirectToRoute('admin');
        }


        // Récupérer les clics par animal
        $clicks = $clickService->getClicksByAnimal();

        // Récupération des paramètres de filtrage
        $filterDate = $request->query->get('date');
        $filterRace = $request->query->get('race');

        // Création de la requête pour les rapports vétérinaires
        $qb = $reportRepository->createQueryBuilder('r')
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

        // Récupération des labels de races d'animaux pour le formulaire de tri
        $races = $entityManager->createQuery('SELECT DISTINCT rac.label FROM App\Entity\Animal a JOIN a.race rac')
            ->getScalarResult();

        return $this->render('admin/dashboard.html.twig', [
            'userCreationForm' => $userForm->createView(),
            'services' => $serviceRepository->findAll(),
            'clicks' => $clicks,
            'rapportsVeterinaire' => $rapportsVeterinaire,
            'races' => array_column($races, 'label'),  // 'label' est le champ retourné par la requête DQL
        ]);
    }
}
