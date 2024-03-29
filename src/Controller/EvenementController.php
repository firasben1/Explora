<?php

namespace App\Controller;

use App\Entity\Evenement;
use App\Entity\Images;
use App\Form\EvenementType;
use App\Repository\EvenementRepository;
use App\Repository\ImagesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/evenement')]
class EvenementController extends AbstractController
{
    #[Route('/', name: 'app_evenement_index', methods: ['GET'])]
public function index(EvenementRepository $evenementRepository,Request $request,PaginatorInterface $paginator): Response
{
    $evenements = $evenementRepository->findAll();
    $data = $evenementRepository->findAll();
    $evenements = $paginator->paginate(
    $data, /* query NOT result */
    $request->query->getInt('page', 1), /*page number*/
   4/*limit per page*/
    );
  
    return $this->render('evenement/index.html.twig', [
        'evenements' => $evenements
    ]);
}

#[Route('/User', name: 'app_evenement_index_User', methods: ['GET'])]
public function indexUser(EvenementRepository $evenementRepository,Request $request,PaginatorInterface $paginator): Response
{
    $evenements = $evenementRepository->findAll();
    $data = $evenementRepository->findAll();
    $evenements = $paginator->paginate(
    $data, /* query NOT result */
    $request->query->getInt('page', 1), /*page number*/
   6/*limit per page*/
    );
  

    return $this->render('evenement/indexUser.html.twig', [
        'evenements' => $evenements
    ]);
}

    #[Route('/new', name: 'app_evenement_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $evenement = new Evenement();
        $form = $this->createForm(EvenementType::class, $evenement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $entityManager->persist($evenement);
            $entityManager->flush();

            return $this->redirectToRoute('app_evenement_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('evenement/new.html.twig', [
            'evenement' => $evenement,
            'form' => $form,
        ]);
    }

    #[Route('/delete/{id}/delete', name: 'app_evenement_delete', methods: ['POST','GET'])]
    public function delete(Request $request, Evenement $evenement, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$evenement->getId(), $request->request->get('_token'))) {
            $entityManager->remove($evenement);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_evenement_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/{id}', name: 'app_evenement_show', methods: ['GET'])]
    public function show(Evenement $evenement): Response
    {
        return $this->render('evenement/show.html.twig', [
            'evenement' => $evenement,
        ]);
    }

    #[Route('/User/{id}', name: 'app_evenement_show_User', methods: ['GET'])]
    public function showUser(Evenement $evenement): Response
    {
        return $this->render('evenement/showUser.html.twig', [
            'evenement' => $evenement,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_evenement_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Evenement $evenement, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(EvenementType::class, $evenement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_evenement_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('evenement/edit.html.twig', [
            'evenement' => $evenement,
            'form' => $form,
        ]);
    }
    #[Route('/search/ajax', name: 'app_ajax_evenement', methods: ['GET'])]
    public function ajaxEvenement(Request $request,EvenementRepository $evenementRepository): Response
    {
        $requestString=$request->get('searchValue');
        $evenements = $evenementRepository->findEvenementAjax($requestString);
        $eventImages = [];
       
        return $this->render('evenement/ajax.html.twig', [
            'evenements' => $evenements,
        ]);
    }
    
}