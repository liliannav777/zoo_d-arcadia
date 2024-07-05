<?php

namespace App\Controller;

use App\Entity\Avis;
use App\Repository\AvisRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{

    private $avisRepository;
    private $entityManager;

    public function __construct(AvisRepository $avisRepository, EntityManagerInterface $entityManager)
    {
        $this->avisRepository = $avisRepository;
        $this->entityManager = $entityManager;
    }

    #[Route('/', name: 'Accueil')]
    public function index(): Response
    {   
        $avisList =  $this->avisRepository->findAll();

        return $this->render('home.html.twig', [
            'avisList' => $avisList,
        ]);
    }

    #[Route('/submit-avis', name: 'submit_avis', methods: ['POST'])]
    public function submitAvis(Request $request): Response
    {
        $pseudo = $request->request->get('pseudo');
        $commentaire = $request->request->get('commentaire');
        $note = (int) $request->request->get('note');

        if ($pseudo && $commentaire && $note) {
            $avis = new Avis();
            $avis->setPseudo($pseudo);
            $avis->setCommentaire($commentaire);
            $avis->setNote($note);
            $avis->setVisible(false);

            $this->entityManager->persist($avis);
            $this->entityManager->flush();

            $this->addFlash('success', 'Votre avis a été soumis avec succès et est en attente de validation.');

        return $this->redirectToRoute('Accueil');
    }
}

}
