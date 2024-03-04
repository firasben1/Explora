<?php

namespace App\Controller;

use App\Entity\Commentaire;
use App\Entity\Dislike;
use App\Entity\Like;
use App\Form\CommentaireType;
use App\Repository\CommentaireRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use MercurySeries\FlashyBundle\FlashyNotifier;

#[Route('/commentaire')]
class CommentaireController extends AbstractController
{
    #[Route('/', name: 'app_commentaire_index', methods: ['GET'])]
    public function index(CommentaireRepository $commentaireRepository): Response
    {
        return $this->render('commentaire/index.html.twig', [
            'commentaires' => $commentaireRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_commentaire_new', methods: ['GET', 'POST'])]
    public function new(Request $request, CommentaireRepository $commentaireRepository,UserRepository $ur): Response
    {
        $commentaire = new Commentaire();
        $commentaire->setDate(new \DateTime('now'));
        $form = $this->createForm(CommentaireType::class, $commentaire);
        $form->handleRequest($request);
        $user=$this->getUser();
        $commentaire->setuser($user);
       
        if ($form->isSubmitted() && $form->isValid()) {
            $commentaireRepository->save($commentaire, true);

            return $this->redirectToRoute('app_commentaire_index', [ 'commentaire' => $commentaire], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('commentaire/new.html.twig', [
            'commentaire' => $commentaire,
            'form' => $form,
        ]);
      
    }

    #[Route('/{id}', name: 'app_commentaire_show', methods: ['GET'])]
    public function show(Commentaire $commentaire): Response
    {
        return $this->render('commentaire/show.html.twig', [
            'commentaire' => $commentaire,
        ]);
        
    }
    #[Route('/{id}/edit', name: 'app_commentaire_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Commentaire $commentaire, EntityManagerInterface $entityManager,FlashyNotifier $flashy): Response
    {
        $form = $this->createForm(CommentaireType::class, $commentaire);
        $form->handleRequest($request);
        

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            $flashy->primary('Commentaire modifié!', 'http://your-awesome-link.com');


            return $this->redirectToRoute('app_article_show', ['id'=> $commentaire->getArticle()->getId(),], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('commentaire/edit.html.twig', [
            'commentaire' => $commentaire,
            'form' => $form,
        ]);          
     }
        
    #[Route('/supprimerfront/{id}', name: 'app_commentaire_deletefront', )]
    public function deletee(Request $request, Commentaire $commentaire, CommentaireRepository $commentaireRepository,FlashyNotifier $flashy): Response
    {
            $article=$commentaire->getArticle();
            $article->setNbcommentaire($article->getNbcommentaire()-1);
            $commentaireRepository->remove($commentaire, true);  
            $flashy->error('Commentaire supprimé!', 'http://your-awesome-link.com');
    

            return $this->redirectToRoute('app_article_show', ['id'=> $commentaire->getArticle()->getId(),], Response::HTTP_SEE_OTHER);

    }

    #[Route('/supprimer/{id}', name: 'app_commentaire_delete', )]
    public function delete(Request $request, Commentaire $commentaire, CommentaireRepository $commentaireRepository,FlashyNotifier $flashy): Response
    {
            $article=$commentaire->getArticle();
            $article->setNbcommentaire($article->getNbcommentaire()-1);    
            $commentaireRepository->remove($commentaire, true);
            $flashy->error('Commentaire supprimé!', 'http://your-awesome-link.com');


            return $this->redirectToRoute('app_article_show_back', ['id'=> $commentaire->getArticle()->getId(),], Response::HTTP_SEE_OTHER);

    }
    
    #[Route('/like/{id}', name: 'app_commentaire_like', )]
    public function like(Request $request, Commentaire $id, FlashyNotifier $flashy,EntityManagerInterface $entityManager): Response
    {        $dislikeee=$entityManager->getRepository(Dislike::class)->findoneBy(['commentaire'=>$id->getId(),'user'=>$this->getUser()]);
        $likeee=$entityManager->getRepository(Like::class)->findBy(['commentaire'=>$id->getId(),'user'=>$this->getUser()]);
    if($likeee==null){

        $id->setNblike($id->getNblike()+1);
        $like = new Like();
        $like->setCommentaire($id);
        $user=$this->getUser();
        $like->setUser($user);
        $entityManager->persist($like);
        if($dislikeee!=null)
        {
            $id->setNbdislike($id->getNbdislike()-1);
            $entityManager->remove($dislikeee);

        }
        $entityManager->flush();}
    else
    {
        dump($likeee);
        $flashy->error('vous avez deja liker ce commentaire', 'http://your-awesome-link.com');
    }
    return $this->redirectToRoute('app_article_show',['id'=> $id->getArticle()->getId()]);       


    }

    #[Route('/dislike/{id}', name: 'app_commentaire_dislike', )]

    public function dislike(Request $request, Commentaire $id, FlashyNotifier $flashy,EntityManagerInterface $entityManager): Response
    {        $likeee=$entityManager->getRepository(Like::class)->findoneBy(['commentaire'=>$id->getId(),'user'=>$this->getUser()]);
        $dislikeee=$entityManager->getRepository(Dislike::class)->findBy(['commentaire'=>$id->getId(),'user'=>$this->getUser()]);
    if($dislikeee==null){

        $id->setNbdislike($id->getNbdislike()+1);
        $dislike = new Dislike();
        $dislike->setCommentaire($id);
        $user=$this->getUser();
        $dislike->setUser($user);
        $entityManager->persist($dislike);
        if($likeee!=null)
        {
            $id->setNblike($id->getNblike()-1);
            $entityManager->remove($likeee);

        }
        $entityManager->flush();}
    else
    {
        dump($dislikeee);
        $flashy->error('vous avez deja disliker ce commentaire', 'http://your-awesome-link.com');
    }
    return $this->redirectToRoute('app_article_show',['id'=> $id->getArticle()->getId()]);       


    }
}