<?php

namespace App\Controller;

use App\Entity\Animal;
use App\Entity\Habitat;
use App\Entity\Image;
use App\Entity\Race;
use App\Form\AnimalType;
use App\Form\HabitatType;
use App\Repository\AnimalRepository;
use App\Repository\HabitatRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HabitatController extends AbstractController
{
    #[Route('/habitats', name: 'habitat')]
    public function index(Request $request, EntityManagerInterface $em): Response
    {
        $habitats = $em->getRepository(Habitat::class)->findAll();
    
        $habitat = new Habitat();
        $form = $this->createForm(HabitatType::class, $habitat);
        $form->handleRequest($request);
    
        $editId = $request->query->get('update');
        if ($editId) {
            $habitat = $em->getRepository(Habitat::class)->find($editId);
            if (!$habitat) {
                throw $this->createNotFoundException('Aucun habitat trouvé pour l\'id ' . $editId);
            }
            $form = $this->createForm(HabitatType::class, $habitat);
            $form->handleRequest($request);
        }
    
        if ($form->isSubmitted() && $form->isValid()) {
            $imageFile = $form->get('imageFile')->getData();
    
            if ($imageFile) {
                $imageData = file_get_contents($imageFile->getPathname());
                $image = new Image();
                $image->setImageData($imageData);
                $em->persist($image);
                $habitat->addImage($image);
            }
    
            $em->persist($habitat);
            $em->flush();
            $this->addFlash('success', 'Habitat enregistré avec succès !');
            return $this->redirectToRoute('habitat');
        }
    
        $deleteId = $request->query->get('delete');
        if ($deleteId) {
            $habitat = $em->getRepository(Habitat::class)->find($deleteId);
            if ($habitat) {
                $em->remove($habitat);
                $em->flush();
                $this->addFlash('success', 'Habitat supprimé avec succès !');
            } else {
                $this->addFlash('error', 'Habitat non trouvé.');
            }
            return $this->redirectToRoute('habitat');
        }

        return $this->render('habitat/habitat.html.twig', [
            'habitats' => $habitats,
            'habitatForm' => $form->createView(),
            'isEditing' => $editId !== null,  // Ajouter ce flag pour savoir si on est en mode édition
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

        // Formulaire de création ou de modification d'un animal
        $animalUpdateId = $request->query->get('update');
        $animal = $animalUpdateId ? $animalRepository->find($animalUpdateId) : new Animal();
        $animal->setHabitat($habitat);
        $animalType = $this->createForm(AnimalType::class, $animal);
        $animalType->handleRequest($request);

        if ($animalType->isSubmitted() && $animalType->isValid()) {
            $newRaceName = $animalType->get('newRace')->getData();
            if ($newRaceName) {
                $race = new Race();
                $race->setLabel($newRaceName);
                $entityManager->persist($race);
                $animal->setRace($race);
            }

            $imageFile = $animalType->get('imageFile')->getData();
            if ($imageFile) {
                $imagePath = $this->getParameter('kernel.project_dir') . '/public/assets/styles/images/animals/';
                $imageName = uniqid() . '.' . $imageFile->guessExtension();
                $imageFile->move($imagePath, $imageName);
                $animal->setImagePath($imageName); // Enregistrez le nom du fichier d'image dans l'entité
            }

            $entityManager->persist($animal);
            $entityManager->flush();

            $this->addFlash(
                $animalUpdateId ? 'success' : 'success',
                $animalUpdateId ? 'Animal mis à jour avec succès !' : 'Animal créé avec succès !'
            );

            return $this->redirectToRoute('animal', ['habitat_id' => $habitat_id]);
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
            'animalUpdateId' => $animalUpdateId,
        ]);
    }
}