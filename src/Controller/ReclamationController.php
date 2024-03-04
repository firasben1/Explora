<?php

namespace App\Controller;

use App\Entity\Reclamation;
use App\Form\ReclamationType;
use App\Repository\ReclamationRepository;
use App\Repository\ReponseRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
//use Symfony\Component\BrowserKit\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use MercurySeries\FlashyBundle\DependencyInjection\MercurySeriesFlashyExtension;
use MercurySeries\FlashyBundle\FlashyNotifier;
use Snipe\BanBuilder\CensorWords;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Knp\Component\Pager\PaginatorInterface;


class ReclamationController extends AbstractController
{
    //show
#[Route('/reclamatio/{id}', name: 'reclamation_details')]
public function show(ReclamationRepository $recrepository, $id): Response
{
    // Récupérer la réclamation en fonction de l'ID
    $reclamation = $recrepository->find($id);

    // Vérifier si la réclamation existe
    if (!$reclamation) {
        throw $this->createNotFoundException('Reclamation not found');
    }

    // Censurer les mots interdits dans la description de la réclamation
    $censor = new CensorWords;
    $langs = array('fr','it','en-us','en-uk','es');
    $badwords = $censor->setDictionary($langs);
    $censor->setReplaceChar("*");
    $string = $censor->censorString($reclamation->getDescription());
    $reclamation->setDescription($string['clean']);

    // Passer l'objet réclamation censurée au template
    return $this->render('reclamation/show.html.twig', [
        'rec' => $reclamation,
    ]);
}


    //showback
    #[Route('/det/back/{id}', name: 'app_reclamation_show_back', methods: ['GET'])]
    public function showBack(int $id, ReclamationRepository $rec): Response
    {
        $reclamation = $rec->find($id);
        
        if (!$reclamation) {
            throw $this->createNotFoundException('Reclamation not found');
        }
    
        // Build the image path based on your directory structure
        $imagePath = 'public/uploads/images/' . $reclamation->getImage();
    
        return $this->render('reclamation/showback.html.twig', [
            'reclamation' => $reclamation,
            'image_path' => $imagePath,
        ]);
    }


//list
#[Route('/reclamation/list', name: 'list_reclamation')]
public function listReclamation(Request $request, ReclamationRepository $reclamationRepository, PaginatorInterface $paginator): Response
{

        $reclamation = $reclamationRepository->findAll();
        $data = $reclamationRepository->findAll();
        $reclamation = $paginator->paginate(
        $data, /* query NOT result */
        $request->query->getInt('page', 1), /*page number*/
       6/*limit per page*/
        );
        
    // Get the combined sorting field and order value from the request
    $sortParam = $request->query->get('sort_by');

    // Default sorting options
    $sortBy = 'id'; // Default sorting field
    $sortOrder = 'asc'; // Default sorting order
   

    // Parse the sorting parameter if it's not empty
    if ($sortParam) {
        // Explode the sorting parameter if it's not empty
        $parts = explode('_', $sortParam);
        
        // Check if the exploded array has at least two elements
        if (count($parts) >= 2) {
            // Extract the field and order from the sorting parameter
            $field = $parts[0];
            $order = $parts[1];

            // Validate the field and order
            $validFields = ['id', 'date'];
            $validOrders = ['asc', 'desc'];

            if (in_array($field, $validFields) && in_array($order, $validOrders)) {
                // Use the parsed field and order
                $sortBy = $field;
                $sortOrder = $order;
            }
        }
    }


    // Fetch reclamations with their related responses
    $reclamations = $reclamationRepository->findAllWithResponsesSortedBy($sortBy, $sortOrder);

    // Render the template with sorted reclamations
    return $this->render('reclamation/list.html.twig', [
        'reclamations' => $reclamations,
        'reclamation' => $reclamation,

    ]);
}


  
    
    //list back
    #[Route('/back', name: 'app_reclamation_index_back', methods: ['GET'])]
    public function index_back(ReclamationRepository $recRepository,Request $request,FlashyNotifier $flashy,PaginatorInterface $paginator): Response
    {
        $recl = $recRepository->findAll();
        $recl = $recRepository->findAll();
        $data = $recRepository->findAll();
        $recl = $paginator->paginate(
        $data, /* query NOT result */
        $request->query->getInt('page', 1), /*page number*/
       6/*limit per page*/
        );
        
        
        return $this->render('reclamation/index2.html.twig', [
            'reclamations' => $recl,
        ]);
    }

    

    #[Route('/addReclamation', name: 'add_reclamation')]
    public function new(Request $request, EntityManagerInterface $entityManager, FlashyNotifier $flashy): Response
    {
        $rec = new Reclamation();
        
        $form = $this->createForm(ReclamationType::class, $rec);
        $form->handleRequest($request);
        $rec->setDate(new \DateTime('now'));
        $user=$this->getUser();
        $rec->setuser($user);
      
        if ($form->isSubmitted() && $form->isValid()) {
            // Persist the new reclamation entity
            $entityManager->persist($rec);
            $entityManager->flush();
            
            // Add a success flash message
            $flashy->success('Reclamation ajoutée avec succès!', 'http://your-awesome-link.com');
    
            return $this->redirectToRoute('list_reclamation', [], Response::HTTP_SEE_OTHER);
        }
        
        return $this->renderForm('reclamation/add.html.twig', [
            'rec' => $rec,  
            'form' => $form,
        ]);        
    }
    


    #[Route('/{id}/edit', name: 'app_Reclamation_edit', methods: ['GET', 'POST'])]
public function edit(Request $request, int $id, ReclamationRepository $reclamationRepository, EntityManagerInterface $entityManager, FlashyNotifier $flashy): Response
{
    $reclamation = $reclamationRepository->find($id);

    if (!$reclamation) {
        throw $this->createNotFoundException('Reclamation not found');
    }

    $form = $this->createForm(ReclamationType::class, $reclamation);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        // No need to call persist, changes to the managed entity will be saved automatically
        $entityManager->flush();
        $flashy->primary('Reclamation modifié!', 'http://your-awesome-link.com');

        return $this->redirectToRoute('list_reclamation', [], Response::HTTP_SEE_OTHER);
    }

    return $this->renderForm('Reclamation/editreclamation.html.twig', [
        'reclamation' => $reclamation,
        'form' => $form,
    ]);
}

  #[Route('/delete/{id}', name: 'app_Reclamation_delete')]
public function delete(Request $request, int $id, ReclamationRepository $reclamationRepository, EntityManagerInterface $entityManager, FlashyNotifier $flashy): RedirectResponse
{
    $reclamation = $reclamationRepository->find($id);
    
    if (!$reclamation) {
        throw $this->createNotFoundException('Reclamation not found');
    }

    // Remove the entity
    $entityManager->remove($reclamation);
    $entityManager->flush();

    $flashy->error('Reclamation supprimé!', 'http://your-awesome-link.com');

    return $this->redirectToRoute('list_reclamation', [], Response::HTTP_SEE_OTHER);
}

        #[Route('/delete/back/{id}', name: 'app_reclamation_delete_back')]
        public function delete_back(Request $request, $id, EntityManagerInterface $entityManager,Reclamation $reclamation, ReclamationRepository $reclamationRepository): Response
        {
            // Fetch the Reclamation entity by ID
           /* $reclamation = $this->getDoctrine()->getRepository(Reclamation::class)->find($id);
            
            

            // Remove the Reclamation entity
            $entityManager->remove($reclamation);
            $entityManager->flush();*/

            $reclamationRepository->remove($reclamation, true);
        
            return $this->redirectToRoute('app_reclamation_index_back', [], Response::HTTP_SEE_OTHER);
        }

        #[Route('/response/{id}', name: 'response_details')]
        public function responseDetails($id, ReponseRepository $reponseRepository): Response
        {
            $response = $reponseRepository->find($id);
    
            if (!$response) {
                throw $this->createNotFoundException('Response not found');
            }
    
            return $this->render('reclamation/response_details.html.twig', [
                'response' => $response,
            ]);}
    }



                       