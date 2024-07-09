<?php

namespace App\Controller;

use App\Entity\Animal;
use App\Entity\Habitat;
use App\Form\AnimalType;
use App\Form\HabitatType;
use App\Repository\AnimalRepository;
use App\Repository\HabitatRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

class HabitatController extends AbstractController
{
    #[Route('/noshabitats', name: 'habitat')]
    public function index(
        Request $request,
        EntityManagerInterface $entityManager,
        HabitatRepository $habitatRepository
    ): Response {
        $habitats = $habitatRepository->findAll();

        // Formulaire de création d'un nouvel habitat
        $habitat = new Habitat();
        $habitatType = $this->createForm(HabitatType::class, $habitat);
        $habitatType->handleRequest($request);

        if ($habitatType->isSubmitted() && $habitatType->isValid() && $request->request->has('create')) {
            $entityManager->persist($habitat);
            $entityManager->flush();
            $this->addFlash('success', 'Habitat créé avec succès !');
            return $this->redirectToRoute('habitat');
        }

        // Formulaire de mise à jour d'un habitat
        $habitatUpdateId = $request->query->get('update');
        $habitatToUpdate = $habitatUpdateId ? $habitatRepository->find($habitatUpdateId) : null;
        $habitatUpdateType = null;

        if ($habitatToUpdate) {
            $habitatUpdateType = $this->createForm(HabitatType::class, $habitatToUpdate);
            $habitatUpdateType->handleRequest($request);

            if ($habitatUpdateType->isSubmitted() && $habitatUpdateType->isValid() && $request->request->has('update')) {
                $entityManager->flush();
                $this->addFlash('success', 'Habitat mis à jour avec succès !');
                return $this->redirectToRoute('habitat');
            }
        }

        // Suppression de l'habitat
        $habitatDeleteId = $request->query->get('delete');
        if ($habitatDeleteId) {
            $habitatToDelete = $habitatRepository->find($habitatDeleteId);
            if ($habitatToDelete) {
                $entityManager->remove($habitatToDelete);
                $entityManager->flush();
                $this->addFlash('success', 'Habitat supprimé avec succès !');
            } else {
                $this->addFlash('error', 'Habitat non trouvé.');
            }
            return $this->redirectToRoute('habitat');
        }

        return $this->render('habitat/habitat.html.twig', [
            'habitats' => $habitats,
            'habitatType' => $habitatType->createView(),
            'habitatUpdateType' => $habitatUpdateType ? $habitatUpdateType->createView() : null,
            'habitatUpdateId' => $habitatToUpdate ? $habitatToUpdate->getHabitatId() : null,
        ]);
    }

    #[Route('/noshabitats/{habitat_id}', name: 'animal')]
    public function detail(
        $habitat_id, 
        HabitatRepository $habitatRepository,
        AnimalRepository $animalRepository,
        Request $request,
        EntityManagerInterface $entityManager
    ): Response {
        $habitat = $habitatRepository->find($habitat_id);

        if (!$habitat) {
            throw $this->createNotFoundException('Habitat not found');
        }

        $animaux = $habitat->getAnimal();

        // Formulaire de création d'un nouvel animal
        $animal = new Animal();
        $animal->setHabitat($habitat);
        $animalType = $this->createForm(AnimalType::class, $animal);
        $animalType->handleRequest($request);

        if ($animalType->isSubmitted() && $animalType->isValid() && $request->request->has('create')) {
            $entityManager->persist($animal);
            $entityManager->flush();
            $this->addFlash('success', 'Animal créé avec succès !');
            return $this->redirectToRoute('animal', ['habitat_id' => $habitat_id]);
        }

        // Formulaire de mise à jour d'un animal
        $animalUpdateId = $request->query->get('update');
        $animalToUpdate = $animalUpdateId ? $animalRepository->find($animalUpdateId) : null;
        $animalUpdateType = null;

        if ($animalToUpdate) {
            $animalUpdateType = $this->createForm(AnimalType::class, $animalToUpdate);
            $animalUpdateType->handleRequest($request);

            if ($animalUpdateType->isSubmitted() && $animalUpdateType->isValid() && $request->request->has('update')) {
                $entityManager->flush();
                $this->addFlash('success', 'Animal mis à jour avec succès !');
                return $this->redirectToRoute('animal', ['habitat_id' => $habitat_id]);
            }
        }

        // Suppression de l'animal
        $animalDeleteId = $request->query->get('delete');
        if ($animalDeleteId) {
            $animalToDelete = $animalRepository->find($animalDeleteId);
            if ($animalToDelete) {
                $entityManager->remove($animalToDelete);
                $entityManager->flush();
                $this->addFlash('success', 'Animal supprimé avec succès !');
            } else {
                $this->addFlash('error', 'Animal non trouvé.');
            }
            return $this->redirectToRoute('animal', ['habitat_id' => $habitat_id]);
        }

        return $this->render('habitat/animal.html.twig', [
            'habitat' => $habitat,
            'animaux' => $animaux,
            'animalType' => $animalType->createView(),
            'animalUpdateType' => $animalUpdateType ? $animalUpdateType->createView() : null,
            'animalUpdateId' => $animalToUpdate ? $animalToUpdate->getAnimalId() : null,
        ]);
    }
}