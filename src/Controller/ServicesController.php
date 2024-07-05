<?php

namespace App\Controller;

use App\Repository\ServiceRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ServicesController extends AbstractController
{
    #[Route('/nosservices', name: 'services')]
    public function index(ServiceRepository $serviceRepository): Response
    {   
        $services = $serviceRepository->findAll();

        return $this->render('services/services.html.twig', [
            'services' => $services,
        ]);
    }
}
