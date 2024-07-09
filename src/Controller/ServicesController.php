<?php

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

        // Formulaire de création d'un nouveau service
        $service = new Service();
        $serviceType = $this->createForm(ServiceType::class, $service);
        $serviceType->handleRequest($request);

        if ($serviceType->isSubmitted() && $serviceType->isValid() && $request->request->has('create')) {
            /** @var UploadedFile $file */
            $file = $serviceType->get('imagePath')->getData();

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
            $this->addFlash('success', 'Service créé avec succès !');
            return $this->redirectToRoute('services');
        }

        // Formulaire de mise à jour d'un service
        $serviceUpdateId = $request->query->get('update');
        $serviceToUpdate = $serviceUpdateId ? $serviceRepository->find($serviceUpdateId) : null;
        $serviceUpdateType = null;

        if ($serviceToUpdate) {
            $serviceUpdateType = $this->createForm(ServiceType::class, $serviceToUpdate);
            $serviceUpdateType->handleRequest($request);

            if ($serviceUpdateType->isSubmitted() && $serviceUpdateType->isValid() && $request->request->has('update')) {
                $file = $serviceUpdateType->get('imagePath')->getData();

                if ($file) {
                    $newFilename = uniqid().'.'.$file->guessExtension();
                    try {
                        $file->move($this->getParameter('kernel.project_dir').'/public/assets/styles/images/services/', $newFilename);
                    } catch (FileException $e) {
                        $this->addFlash('error', 'Erreur lors de l\'upload de l\'image.');
                        return $this->redirectToRoute('services');
                    }
                    $serviceToUpdate->setImagePath('assets/styles/images/services/'.$newFilename);
                }

                $entityManager->flush();
                $this->addFlash('success', 'Service mis à jour avec succès !');
                return $this->redirectToRoute('services');
            }
        }

        // Suppression du service
        $serviceDeleteId = $request->query->get('delete');
        if ($serviceDeleteId) {
            $serviceToDelete = $serviceRepository->find($serviceDeleteId);
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
            'serviceType' => $serviceType->createView(),
            'services' => $services,
            'serviceUpdateType' => $serviceUpdateType ? $serviceUpdateType->createView() : null,
            'serviceUpdateId' => $serviceToUpdate ? $serviceToUpdate->getServiceId() : null,
        ]);
    }
}
