<?php

namespace App\Controller;

use App\Entity\Animal;
use App\Entity\Habitat;
use App\Entity\Utilisateur;
use App\Entity\Service;
use App\Form\AnimalType;
use App\Form\HabitatType;
use App\Form\UserCreationFormType;
use App\Form\ServiceType;
use App\Form\VeterinaryReportFilterType;
use App\Repository\AnimalRepository;
use App\Repository\RapportVeterinaireRepository;
use App\Repository\ServiceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
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
        AnimalRepository $animalRepository
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


        $filterForm = $this->createForm(VeterinaryReportFilterType::class, null, [
            'animals' => $animalRepository->findAll(),
        ]);
        $filterForm->handleRequest($request);

        $filters = [];
        if ($filterForm->isSubmitted() && $filterForm->isValid()) {
            $data = $filterForm->getData();
            $filters = $reportRepository->findByFilters($data['animal'], $data['date']);
        }

        // Récupérer les consultations par animal
        $consultations = $reportRepository->countConsultationsByAnimal();


        return $this->render('admin/dashboard.html.twig', [
            'userCreationForm' => $userForm->createView(),
            'services' => $serviceRepository->findAll(),
            'reportFilterForm' => $filterForm->createView(),
            'veterinaryReports' => $filters,
            'consultations' => $consultations,
        ]);
    
}
}