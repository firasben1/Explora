<?php

namespace App\Controller;

use App\Entity\Location;
use App\Entity\Voiture;
use App\Form\LocationType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\LocationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class LocationController extends AbstractController
{
    #[Route('/location/new/{id}', name: 'app_location')]
    public function index(Request $request,EntityManagerInterface $entityManager, MailerInterface $mailer, $id): Response
    {
        
        $voitureRepository = $entityManager->getRepository(Voiture::class);
        $voiture = $voitureRepository->find($id);
        $location = new Location();
        $location->setVoiture($voiture);
        $user=$this->getUser();
        $location->setuser($user);
        $form = $this->createForm(LocationType::class, $location);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($location);
            $entityManager->flush();

            // Envoi de l'e-mail de confirmation
            $this->sendConfirmationEmail($location, $mailer);

            // Rediriger vers une autre page ou afficher un message de succès
            return $this->redirectToRoute('list_locations');
        }

        return $this->render('location/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/location/list', name: 'list_locations')]
    public function listLocations(LocationRepository $locationRepository,Request $request,PaginatorInterface $paginator): Response
    {
        $locations = $locationRepository->findAll();
        $data = $locationRepository->findAll();
        $locations = $paginator->paginate(
        $data, /* query NOT result */
        $request->query->getInt('page', 1), /*page number*/
       5/*limit per page*/
        );
      

        return $this->render('location/list.html.twig', [
            'locations' => $locations,
        ]);
    }

    #[Route('/location/listback', name: 'list_locations_back')]
    public function listLocationsback(LocationRepository $repository): Response
    {
        $locations = $repository->findAll();

        return $this->render('location/listback.html.twig', [
            'locations' => $locations,
        ]);
    }

    private function sendConfirmationEmail(Location $location, MailerInterface $mailer)
{
    // Génération du code d'authentification
    $random_bytes = random_bytes(16);
    $ascii_string = mb_convert_encoding($random_bytes, 'ASCII');
    $slug = (new \Symfony\Component\String\Slugger\AsciiSlugger())->slug($ascii_string)->toString();
    $alphanumeric_code = preg_replace('/[^a-zA-Z0-9]/', '', $slug);
    $final_code = substr($alphanumeric_code, 0, 6);

    // Affectation du code d'authentification à l'entité Location
    $location->setAuthCode($final_code);

    // Création du contenu de l'e-mail avec le code d'authentification
    $emailContent = sprintf(
        'Votre demande de location de voiture a bien été enregistrée. Votre code d\'authentification est : %s',
        $final_code
    );

    // Envoi de l'e-mail de confirmation
    $email = (new Email())
        ->from('sabermadiouni5@gmail.com')
        ->to($location->getMail())
        ->subject('Confirmation de votre demande de location de voiture')
        ->html($emailContent);

    $mailer->send($email);
}

}