<?php

namespace App\Controller;
use App\Entity\Article;

use App\Form\ArticleType;
use App\Entity\Commentaire;
use App\Form\CommentaireType;
use App\Repository\ArticleRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\CommentaireRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Snipe\BanBuilder\CensorWords;
use MercurySeries\FlashyBundle\FlashyNotifier;




#[Route('/article')]
class ArticleController extends AbstractController
{
    #[Route('/', name: 'app_article_index', methods: ['GET'])]
    public function index(ArticleRepository $articleRepository,Request $request,PaginatorInterface $paginator): Response
    {
        $data = $articleRepository->findAll();
        $articles = $paginator->paginate(
        $data, /* query NOT result */
        $request->query->getInt('page', 1), /*page number*/
       1/*limit per page*/
        );
      
    
        return $this->render('article/index1.html.twig', [
            'articles' => $articles,
        ]);
    }
    #[Route('/back', name: 'app_article_index_back', methods: ['GET'])]
    public function index_back(ArticleRepository $articleRepository,Request $request,PaginatorInterface $paginator): Response
    {
        $articles = $articleRepository->findAll();  
        $data = $articleRepository->findAll();
        $articles = $paginator->paginate(
        $data, /* query NOT result */
        $request->query->getInt('page', 1), /*page number*/
       6/*limit per page*/
        );
        
        return $this->render('article/index2.html.twig', [
            'articles' => $articles,
        ]);
    }
    

   
    #[Route('/new', name: 'app_article_new', methods: ['GET', 'POST'])]
    public function new(Request $request ,ArticleRepository $articleRepository,FlashyNotifier $flashy,UserRepository $ur): Response
    {
        $article = new Article();
        
        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);
        $article->setDate(new \DateTime('now'));
        $article->setNbcommentaire(0);
        $user=$this->getUser();
        $article->setuser($user);
      
        if ($form->isSubmitted() && $form->isValid()) {
            $articleRepository->save($article, true);
            $flashy->success('Article ajouté!', 'http://your-awesome-link.com');

            return $this->redirectToRoute('app_article_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('article/new.html.twig', [
            'article' => $article,  
            'form' => $form,
        ]);        
    }

    #[Route('det/front/{id}', name: 'app_article_show', methods: ['GET',"POST"])]
    public function showFront(Request $request,Article $article,CommentaireRepository $cmr,FlashyNotifier $flashy): Response
    {
        $commentaires=$cmr->findBy(["article"=>$article->getId()]);
        $commentaire = new Commentaire();  
        $commentaire->setDate(new \DateTime('now'));
        $form = $this->createForm(CommentaireType::class, $commentaire); 
        $form->handleRequest($request);
       

        if ($form->isSubmitted() && $form->isValid()) {
            $article->setNbcommentaire($article->getNbcommentaire()+1);
            $commentaire->setArticle($article);
            $commentaire->setArticle($article);
            $user=$this->getUser();
            $commentaire->setNbdislike(0);
            $commentaire->setNblike(0);
            $commentaire->setuser($user); 
            $cmr->save($commentaire, true); 
            $article=$commentaire->getArticle();
            $censor = new CensorWords;   
            $langs = array('fr','it','en-us','en-uk','es');
            $badwords = $censor->setDictionary($langs);
            $censor->setReplaceChar("*");
            $string = $censor->censorString($commentaire->getContenu());
            $commentaire->setContenu($string['clean']);
            $flashy->success('Commentaire ajouté!', 'http://your-awesome-link.com');
            $cmr->save($commentaire, true);
            
            return $this->redirectToRoute('app_article_show',['id'=> $article->getId()
        ]);                        
     }
        
                return $this->render('article/show.html.twig', [
                    'article' => $article,'commentaires'=>$commentaires,'form'=>$form->createView(),
             
            
        ]);
    }

    #[Route('det/back/{id}', name: 'app_article_show_back', methods: ['GET'])]
    public function showBack(Article $article,CommentaireRepository $cmr): Response
    {
        $commentaires=$cmr->findBy(["article"=>$article->getId()]);
        return $this->render('article/showback.html.twig', [
            'article' => $article,'commentaires'=>$commentaires
        ]);   
    }



    #[Route('/{id}/edit', name: 'app_article_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Article $article,ArticleRepository $articleRepository,FlashyNotifier $flashy): Response
    {
        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $articleRepository->save($article, true);
            $flashy->primary('Article modifié!', 'http://your-awesome-link.com');

            return $this->redirectToRoute('app_article_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('article/edit.html.twig', [
            'article' => $article,
            'form' => $form,
        ]);
    }

    #[Route('/delete/{id}', name: 'app_article_delete')]
    public function delete(Request $request, Article $articlesh, ArticleRepository $articleRepository,FlashyNotifier $flashy): RedirectResponse
    {
       
            $articleRepository->remove($articlesh, true);
            $flashy->error('Article supprimé!', 'http://your-awesome-link.com');
          
            $articleRepository->flush();

        return $this->redirectToRoute('app_article_index_back', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/delete/back/{id}', name: 'app_article_delete_back')]
    public function delete_back(Request $request, Article $article, ArticleRepository $articleRepository): Response
    {

        $articleRepository->remove($article, true);

        return $this->redirectToRoute('app_article_index_back', [], Response::HTTP_SEE_OTHER);
    }
    
}