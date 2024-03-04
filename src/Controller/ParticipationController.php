<?php

namespace App\Controller;

use App\Entity\Participation;
use App\Entity\Reservation;
use App\Form\ParticipationType;
use App\Repository\ParticipationRepository;
use BaconQrCode\Common\ErrorCorrectionLevel;
use Doctrine\ORM\EntityManagerInterface;
use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mime\Part\DataPart;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

#[Route('/participation')]
class ParticipationController extends AbstractController
{
    #[Route('/', name: 'app_participation_index', methods: ['GET'])]
    public function index(ParticipationRepository $participationRepository): Response
    {
        return $this->render('participation/index.html.twig', [
            'participations' => $participationRepository->findAll(),
        ]);
    }

    #[Route('/new/{nbrParticp}/{id_reservation}/{participantIndex}', name: 'app_participation_new', methods: ['GET', 'POST'])]
    public function new(
        Request $request,
        ParticipationRepository $participationRepository,
        EntityManagerInterface $entityManager,
        int $nbrParticp,
        int $id_reservation,
        SluggerInterface $slugger,
        MailerInterface $mailer,
        int $participantIndex
    ): Response {
        if ($nbrParticp < 1) {
            throw $this->createNotFoundException('Invalid number of participants');
        }

        $reservation = $entityManager->getRepository(Reservation::class)->find($id_reservation);
        if (!$reservation) {
            throw $this->createNotFoundException('Reservation not found');
        }

        $form = $this->createForm(ParticipationType::class, new Participation());
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $participation = $form->getData();
            $participation->setReservation($reservation);

            $entityManager->persist($participation);
            $entityManager->flush();

            // Render HTML page template
            $html = $this->renderView('participation/participation.html.twig', [
                'participation' => $participation,
            ]);

            // Save the HTML page
            $htmlFileName = $slugger->slug($participation->getId() . '_participation.html');
            $htmlFilePath = $this->getParameter('kernel.project_dir') . '/public/uploads/' . $htmlFileName;
            file_put_contents($htmlFilePath, $html);

            // Use the builder to create the QR code
            $result = Builder::create()
                ->writer(new PngWriter())
                ->data($this->generateUrl('app_participation_show_qr', ['id' => $participation->getId()], UrlGeneratorInterface::ABSOLUTE_URL))
                ->encoding(new Encoding('UTF-8'))  // Optional, adjust encoding if needed
                ->size(300)  // Optional, adjust size
                ->margin(10)
                ->build();


            // Generate the file path
            $fileName = $participation->getId() . '-qr-code.png';
            $filePath = $this->getParameter('kernel.project_dir') . '/public/uploads/' . $fileName;
            $result->saveToFile($filePath);

            $part = new DataPart(fopen($filePath, 'r'));
            $part->hasContentId('qr-code');

            $email = (new Email())
            ->from('sabermadouini5@gmail.com')
            ->to($participation->getEmail())
                ->subject('Your QR Ticket');

            $imageData = fopen($filePath, 'r');
            $email->embed($imageData, 'qr-code.png'); // Embed the image data, provide a name for the image

            $htmlContent = '<p>Thank you for participating! Here is your QR Ticket:</p><img src="cid:qr-code.png">';
            $email->html($htmlContent);

            $mailer->send($email);

            if ($participantIndex === $nbrParticp) {
                $this->addFlash('success', 'All participants submitted successfully!');
                return $this->redirectToRoute('app_reservation_index_User');
            } else {

                $participantIndex++;
                $this->addFlash('success', 'Participation saved! Enter details for the next participant.');
                return $this->redirectToRoute('app_participation_new', [
                    'nbrParticp' => $nbrParticp,
                    'id_reservation' => $id_reservation,
                    'participantIndex' => $participantIndex,
                ]);
            }
        }

        return $this->render('participation/new.html.twig', [
            'form' => $form->createView(),
            'nbrParticp' => $nbrParticp,
            'id_reservation' => $id_reservation,
            'participantIndex' => $participantIndex,
        ]);
    }



    #[Route('/{id}', name: 'app_participation_show', methods: ['GET'])]
    public function show(Participation $participation): Response
    {
        return $this->render('participation/show.html.twig', [
            'participation' => $participation,
        ]);
    }

    #[Route('/qr/{id}', name: 'app_participation_show_qr', methods: ['GET'])]
    public function showQrTicket(Participation $participation): Response
    {
        return $this->render('participation/participation.html.twig', [
            'participation' => $participation,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_participation_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Participation $participation, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ParticipationType::class, $participation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_participation_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('participation/edit.html.twig', [
            'participation' => $participation,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_participation_delete', methods: ['POST'])]
    public function delete(Request $request, Participation $participation, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$participation->getId(), $request->request->get('_token'))) {
            $entityManager->remove($participation);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_participation_index', [], Response::HTTP_SEE_OTHER);
    }
}