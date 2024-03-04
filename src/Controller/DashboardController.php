<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Commentaire;
class DashboardController extends AbstractController
{
    #[Route('/dashboard', name: 'app_dashboard')]
    public function index(): Response
    {
        return $this->render('base2.html.twig', [
            'controller_name' => 'DashboardController',
        ]);
    }


    #[Route('/dashboard1', name: 'app_dashboard1')]

    public function statistiquesCommentaires(): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        
        // Récupérer les commentaires groupés par blog
        $commentairesParBlog = $entityManager->getRepository(Commentaire::class)->getCommentairesParBlog();

        $labels = [];
        $data = [];
        foreach ($commentairesParBlog as $commentaire) {
            $labels[] = $commentaire['titre']; // Supposons que 'titre' est le champ contenant le nom du blog
            $data[] = $commentaire['nombreCommentaires'];
        }

        return $this->render('basestat.html.twig', [
            'labels' => json_encode($labels),
            'data' => json_encode($data),
        ]);
    }
}