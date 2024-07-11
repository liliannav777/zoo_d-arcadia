<?php

// src/Controller/ServicesController.php

namespace App\Controller;

use App\Entity\Service;
use App\Form\ServiceType;
use App\Repository\ServiceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

class ServicesController extends AbstractController
{
    #[Route('/nosservices', name: 'services')]
    public function index(
        Request $request,
        EntityManagerInterface $entityManager,
        ServiceRepository $serviceRepository
    ): Response {
        $services = $serviceRepository->findAll();

        // Déterminer si nous sommes en mode création ou modification
        $service = new Service();
        $isEditing = false;

        $editId = $request->query->get('update');
        if ($editId) {
            $service = $serviceRepository->find($editId);
            if (!$service) {
                throw $this->createNotFoundException('Aucun service trouvé pour l\'id ' . $editId);
            }
            $isEditing = true;
        }

        // Créer le formulaire pour le service
        $form = $this->createForm(ServiceType::class, $service);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $file = $form->get('imagePath')->getData();
            if ($file) {
                $newFilename = uniqid().'.'.$file->guessExtension();
                try {
                    $file->move($this->getParameter('kernel.project_dir').'/public/assets/styles/images/services/', $newFilename);
                } catch (FileException $e) {
                    $this->addFlash('error', 'Erreur lors de l\'upload de l\'image.');
                    return $this->redirectToRoute('services');
                }
                $service->setImagePath('assets/styles/images/services/'.$newFilename);
            }

            $entityManager->persist($service);
            $entityManager->flush();

            $this->addFlash('success', $isEditing ? 'Service mis à jour avec succès !' : 'Service créé avec succès !');
            return $this->redirectToRoute('services');
        }

        // Suppression du service
        $deleteId = $request->query->get('delete');
        if ($deleteId) {
            $serviceToDelete = $serviceRepository->find($deleteId);
            if ($serviceToDelete) {
                $entityManager->remove($serviceToDelete);
                $entityManager->flush();
                $this->addFlash('success', 'Service supprimé avec succès !');
            } else {
                $this->addFlash('error', 'Service non trouvé.');
            }
            return $this->redirectToRoute('services');
        }

        return $this->render('services/services.html.twig', [
            'services' => $services,
            'serviceForm' => $form->createView(),
            'isEditing' => $isEditing,
        ]);
    }
}
