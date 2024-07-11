<?php

namespace App\Controller;

use App\Entity\Avis;
use App\Entity\Horaire;
use App\Form\HoraireType;
use App\Repository\AvisRepository;
use App\Repository\HoraireRepository;
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
    public function index(Request $request,  EntityManagerInterface $em): Response
    {   /* Gérer les avis */
        $avisList =  $this->avisRepository->findAll();


        // Récupération de tous les horaires
        $horaires = $em->getRepository(Horaire::class)->findAll();

        $horaire = new Horaire();
        $form = $this->createForm(HoraireType::class, $horaire);

        $editId = $request->query->get('edit');
        if ($editId) {
            $horaire = $em->getRepository(Horaire::class)->find($editId);
            if (!$horaire) {
                throw $this->createNotFoundException('Aucun horaire trouvé pour l\'id '.$editId);
            }
            $form = $this->createForm(HoraireType::class, $horaire);
        }

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            if ($editId) {
                $em->persist($horaire);
            } else {
                $em->persist($data);
            }
            $em->flush();

            return $this->redirectToRoute('Accueil');
        }

        if ($request->query->get('delete')) {
            $deleteId = $request->query->get('delete');
            $horaire = $em->getRepository(Horaire::class)->find($deleteId);
            if ($horaire) {
                $em->remove($horaire);
                $em->flush();
            }
            return $this->redirectToRoute('Accueil');
        }

        return $this->render('home.html.twig', [
            'avisList' => $avisList,
            'horaires' => $horaires,
            'form' => $form->createView(),
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
