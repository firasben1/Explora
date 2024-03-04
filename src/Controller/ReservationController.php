<?php

namespace App\Controller;

use App\Entity\Reservation;
use App\Entity\User;
use App\Form\ReservationType;
use App\Repository\EvenementRepository;
use App\Repository\ReservationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/reservation')]
class ReservationController extends AbstractController
{
    #[Route('/', name: 'app_reservation_index', methods: ['GET'])]
    public function index(ReservationRepository $reservationRepository): Response
    {
        return $this->render('reservation/index.html.twig', [
            'reservations' => $reservationRepository->findAll(),
        ]);
    }

    #[Route('/User', name: 'app_reservation_index_User', methods: ['GET'])]
    public function indexUser(ReservationRepository $reservationRepository): Response
    {
        return $this->render('reservation/indexUser.html.twig', [
            'reservations' => $reservationRepository->findAll(),
        ]);
    }

    #[Route('/number-input-form/{id_evenement}', name: 'number_participant')]
public function numberInputForm(Request $request,$id_evenement): Response
{
    $form = $this->createFormBuilder()
        ->add('number', IntegerType::class, [
            'label' => 'Enter a number:',
            'attr' => [
                'inputmode' => 'numeric',
                'pattern' => '[0-9]*', 
                'maxlength' => 10, 
            ],
        ])
        ->add('submit', SubmitType::class, ['label' => 'Submit'])
        ->getForm();

    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        return $this->redirectToRoute('app_reservation_new', ['idEvenement' => $id_evenement,'nbrParticp'=>$form->get("number")->getData()], Response::HTTP_SEE_OTHER);

    }

    return $this->render('reservation/number_input_form.html.twig', [
        'form' => $form->createView(),
    ]);
}

    #[Route('/new/{idEvenement}/{nbrParticp}', name: 'app_reservation_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager,$idEvenement,EvenementRepository $evenementsRepository,$nbrParticp): Response
    {
        $reservation = new Reservation(); 
        $user=$this->getUser();
        $reservation->setUser($user);
        $evenment = $evenementsRepository->find($idEvenement);
        if (!$evenment) {
            throw $this->createNotFoundException('Event not found');
        }
        $reservation->setEvenement($evenment);
        $reservation->setEtat("En attente");
        $reservation->setNbParticipant($nbrParticp);
        $entityManager->persist($reservation);
        $entityManager->flush();
        return $this->redirectToRoute('app_participation_new', ['id_reservation' => $reservation->getId(),'nbrParticp'=>$reservation->getNbParticipant(),'participantIndex' => 1], Response::HTTP_SEE_OTHER);
        

    }

    #[Route('/User/{id}', name: 'app_reservation_show_User', methods: ['GET'])]
    public function showUser(Reservation $reservation): Response
    {
        $participants = $reservation->getParticipations();
        return $this->render('reservation/showUser.html.twig', [
            'reservation' => $reservation,
            'participations' => $participants
        ]);
    }

    #[Route('/{id}', name: 'app_reservation_show', methods: ['GET'])]
    public function show(Reservation $reservation): Response
    {
        $participants = $reservation->getParticipations();
        return $this->render('reservation/show.html.twig', [
            'reservation' => $reservation,
            'participations' => $participants
        ]);
    }

    #[Route('/{id}/edit', name: 'app_reservation_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Reservation $reservation, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ReservationType::class, $reservation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_reservation_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('reservation/edit.html.twig', [
            'reservation' => $reservation,
            'form' => $form,
        ]);
    }

}