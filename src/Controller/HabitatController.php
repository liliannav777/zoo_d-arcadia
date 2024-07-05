<?php

// src/Controller/HabitatController.php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\HabitatRepository;

class HabitatController extends AbstractController
{
    #[Route('/noshabitats', name: 'habitat')]
    public function index(HabitatRepository $habitatRepository): Response
    {
        $habitats = $habitatRepository->findAll();

        return $this->render('habitat/habitat.html.twig', [
            'habitats' => $habitats,
        ]);
    }

    #[Route('/noshabitats/{slug}', name: 'habitat_name')]
    public function detail(string $slug, HabitatRepository $habitatRepository): Response
    {
        $habitat = $habitatRepository->findOneBy(['slug' => $slug]);

        if (!$habitat) {
            throw $this->createNotFoundException('Habitat not found');
        }

        $animaux = $habitat->getAnimal();

        return $this->render('habitat/habitat_name.html.twig', [
            'habitat' => $habitat,
            'animaux' => $animaux,
        ]);
    }
}
