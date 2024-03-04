<?php

namespace App\Controller;

use App\Entity\Reclamation;
use App\Entity\Reponse;
use App\Form\ReponseType;
use App\Repository\ReponseRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class ReponseController extends AbstractController
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/detrep/back/{id}', name: 'app_reponse_show_back', methods: ['GET'])]
    public function showBack(int $id, ReponseRepository $reponseRepository): Response
    {
        $reponse = $reponseRepository->find($id);
        
        return $this->render('reponse/showback.html.twig', [
            'reponse' => $reponse
        ]);
    }

    #[Route('/back/reponse', name: 'app_reponse_index_back', methods: ['GET'])]
    public function indexBack(ReponseRepository $reponseRepository): Response
    {
        $reponses = $reponseRepository->findAll();
        
        return $this->render('reponse/index2.html.twig', [
            'reponses' => $reponses,
        ]);
    }


//add


#[Route('/add/reponse/{id}', name: 'add_reponse')]
public function add(Request $request, EntityManagerInterface $entityManager, $id): Response
{
    // Get the Reclamation object based on the provided ID
    $reclamationRepository = $entityManager->getRepository(Reclamation::class);
    $reclamation = $reclamationRepository->find($id);

    // Create a new Reponse object and associate it with the Reclamation
    $reponse = new Reponse();
    $reponse->setReclamation($reclamation);

    // Create the form for adding a response and pass the idReclamation option
    $form = $this->createForm(ReponseType::class, $reponse, [
        'idReclamation' => $reclamation->getId() // Pass the ID of the associated reclamation
    ]);
    $form->handleRequest($request);
    
    // Handle form submission
    if ($form->isSubmitted() && $form->isValid()) {
        // Persist the response object
        $entityManager->persist($reponse);
        $entityManager->flush();
        
        // Send email notification to a static email address
         //$staticEmail = 'bensalahyassine@gmail.com'; // Replace with your static email address
        
        // $email = (new Email())
        /*    ->from('bensalah.yassine@esprit.tn')
            ->to($staticEmail)
            ->subject('Response Added to a Reclamation')
            ->html($this->renderView(
                'reponse/response_notification.html.twig',
                ['reponse' => $reponse]
            ));

        $mailer->send($email);*/

        // Redirect to the appropriate page after successful submission
        return $this->redirectToRoute('app_reponse_index_back');
    }
    
    // Render the add response form template, passing the form
    return $this->renderForm('reponse/add.html.twig', ['form' => $form]);
}






//edit

    #[Route('/editreponse/{id}', name: 'reponse_edit')]
    public function editReponse(Request $request, int $id, ReponseRepository $reponseRepository): Response
    {
        $reponse = $reponseRepository->find($id);
        $form = $this->createForm(ReponseType::class, $reponse);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->flush();
            
            return $this->redirectToRoute('app_reponse_index_back');
        }
        
        return $this->renderForm('reponse/editreponse.html.twig', [
            'reponse' => $reponse,
            'form' => $form
        ]);
    }
}