<?php

namespace App\Controller;

use App\Entity\Location;
use App\Entity\ReponseLocation;
use App\Repository\LocationRepository;
use App\Repository\ReponseLocationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mailer\Transport; 
use Symfony\Bridge\Twig\Mime\TemplatedEmail;

//use Symfony\Component\Mime\Email; // Import de la classe Email

class ReponselocationController extends AbstractController
{
   /* #[Route('/confirmerlocation/{id}', name: 'app_confirmerlocation')]
    public function confirmerLocation(LocationRepository $repository, LocationRepository $locationRepository,  int $id, EntityManagerInterface $entityManager): Response
    {
        // récupérer la location
        $location = $locationRepository->find($id);
        $locations = $repository->findAll();

        if ($location) {
            $reponseLocation = $location->getReponseLocation();

            if ($reponseLocation) {
                $reponseLocation->setStatut('confirmé');
                $entityManager->persist($reponseLocation);
                
            } else {
           
                $reponse = new ReponseLocation();
                $reponse->setStatut('confirmé');
                $location->setReponseLocation($reponse);
                $entityManager->persist($reponse);
                $entityManager->persist($location);
            }
            $entityManager->flush();

          

            $this->addFlash('success', 'La location a été confirmée avec succès.');
        } else {
            $this->addFlash('error', 'La location demandée n\'existe pas.');
        }

        return $this->render('location/listback.html.twig', [
            'locations' => $locations,
        ]);
    }
*/


// ...

#[Route('/confirmerlocation/{id}', name: 'app_confirmerlocation')]

public function confirmerLocation(LocationRepository $repository, LocationRepository $locationRepository, int $id, EntityManagerInterface $entityManager, MailerInterface $mailer): Response
{
    // récupérer la location
    $location = $locationRepository->find($id);
    $locations = $repository->findAll();

    if ($location) {
        $reponseLocation = $location->getReponseLocation();

        if ($reponseLocation) {
            $reponseLocation->setStatut('confirmé');
            $entityManager->persist($reponseLocation);
           
        } else {
            $reponse = new ReponseLocation();
            $reponse->setStatut('confirmé');
            $location->setReponseLocation($reponse);
            $entityManager->persist($reponse);
            $entityManager->persist($location);
            $user = $location->getUser();
            if ($user) {
                $confirmationEmail = (new TemplatedEmail())
                    ->from('sabermadiouni5@gmail.com') // Set your email address
                    ->to($user->getEmail()) // Assuming you have a getEmail() method in your User entity
                    ->subject('Location Confirmed')
                    ->htmlTemplate('reponselocation/confirmation_email.html.twig')
                    ->context([
                        'user' => $user,
                    ]);
    
                $mailer->send($confirmationEmail);
            }
        }
        $user = $location->getUser();
        if ($user) {
            $confirmationEmail = (new TemplatedEmail())
                ->from('sabermadiouni5@gmail.com') // Set your email address
                ->to($user->getEmail()) 
                ->subject('Location Confirmed')
                ->htmlTemplate('reponselocation/confirmation_email.html.twig')
                ->context([
                    'user' => $user,
                ]);

            $mailer->send($confirmationEmail);
        }
        // Send confirmation email to the user
       

        $entityManager->flush();

        $this->addFlash('success', 'La location a été confirmée avec succès.');
    } else {
        $this->addFlash('error', 'La location demandée n\'existe pas.');
    }

    return $this->render('location/listback.html.twig', [
        'locations' => $locations,
    ]);
}

   #[Route('/refuserlocation/{id}', name: 'app_refuserlocation')]
    public function refuserlocation(LocationRepository $repository, LocationRepository $locationRepository, int $id, EntityManagerInterface $entityManager): Response
    {
        // récupérer la location
        $location = $locationRepository->find($id);
        $locations = $repository->findAll();

        if ($location) {
            // récupérer la réponse à la location liée
            $reponseLocation = $location->getReponseLocation();

            if ($reponseLocation) {
                $reponseLocation->setStatut('refusé');
                $entityManager->persist($reponseLocation);
            } else {
                $reponse = new ReponseLocation();
                $reponse->setStatut('refusé');
                $location->setReponseLocation($reponse);
                $entityManager->persist($reponse);
                $entityManager->persist($location);
            }

            $entityManager->flush();

            // Envoyer un e-mail au client

            $this->addFlash('success', 'La location a été refusé avec succès.');
        } else {
            $this->addFlash('error', 'La location demandée n\'existe pas.');
        }

        return $this->render('location/listback.html.twig', [
            'locations' => $locations,
        ]);
    } 
  

}