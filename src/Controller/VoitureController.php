<?php

namespace App\Controller;

use App\Entity\Voiture;
use App\Form\VoitureType;
use App\Repository\VoitureRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;


//INDEX
#[Route('/voiture')]
class VoitureController extends AbstractController
{
    #[Route('/', name: 'app_voiture_index', methods: ['GET'])]
    public function index(Request $request,VoitureRepository $voitureRepository,PaginatorInterface $paginator): Response
    {
        $voitures = $voitureRepository->findAll();
        $data = $voitureRepository->findAll();
        $voitures = $paginator->paginate(
        $data, /* query NOT result */
        $request->query->getInt('page', 1), /*page number*/
       6/*limit per page*/
        );
        return $this->render('voiture/index.html.twig', [
            'voitures' => $voitures
        ]);
    }

    //INDEXF
    #[Route('/FRONT', name: 'app_voiture_indexF', methods: ['GET'])]
    public function indexF(VoitureRepository $voitureRepository,Request $request, PaginatorInterface $paginator): Response
    {  
        $voitures = $voitureRepository->findAll();
        $data = $voitureRepository->findAll();
        $voitures = $paginator->paginate(
        $data, /* query NOT result */
        $request->query->getInt('page', 1), /*page number*/
       6/*limit per page*/
        );
        


        return $this->render('voiture/indexF.html.twig', [
            'voitures' => $voitures,
        ]);
    }

    //ADD
    #[Route('/new', name: 'app_voiture_new', methods: ['GET', 'POST'])]
    public function new(Request $request): Response
    {
        $voiture = new Voiture();
        $form = $this->createForm(VoitureType::class, $voiture);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile $file */
            $tempFilePath = $form['imageName']->getData();
          if ($tempFilePath){
          $destinationPath = "uploads/Voiture". md5(uniqid(rand(), true) .$voiture->getId().".png");
          $compressionQuality = 100;
  
          $this->compressImage($tempFilePath, $destinationPath, $compressionQuality);
  
            $voiture->setImage($destinationPath);
        }
    
    
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($voiture);
            $entityManager->flush();
    
            $this->addFlash('success', 'Voiture ajoutée avec succès !');
    
            return $this->redirectToRoute('app_voiture_index');
        }
    
        return $this->render('voiture/new.html.twig', [
            'voiture' => $voiture,
            'form' => $form->createView(),
        ]);
    }
    
        

  
   //SHOW
   #[Route('/{id}', name: 'app_voiture_show')]
   public function show(VoitureRepository $voiturerepository, $id): Response
   {
       return $this->render('voiture/show.html.twig', [
           'voiture' => $voiturerepository->find($id),
       ]);
   }


    

    //SHOWF
    
     #[Route('/{id}/FRONT', name: 'app_voiture_showF')]
     public function showF(VoitureRepository $voiturerepository, $id): Response
     {
         return $this->render('voiture/showF.html.twig', [
             'voiture' => $voiturerepository->find($id),
         ]);
     }
     
   
   
   //EDIT
#[Route('/{id}/edit', name: 'app_voiture_edit')]
public function editVoiture(Request $request, ManagerRegistry $manager, $id, VoitureRepository $voitureRepository): Response
{
    $em = $manager->getManager();

    $voiture = $voitureRepository->find($id);
    $formx = $this->createForm(VoitureType::class, $voiture);
    $formx->handleRequest($request);

    if ($formx->isSubmitted() && $formx->isValid()) {
        /** @var UploadedFile $file */
        $tempFilePath = $formx['imageName']->getData();
        if ($tempFilePath) {
            $destinationPath = "uploads/Voiture" . md5(uniqid(rand(), true) . $voiture->getId() . ".png");
            $compressionQuality = 100;

            $this->compressImage($tempFilePath, $destinationPath, $compressionQuality);

            $voiture->setImage($destinationPath);
        }

        $em->flush();
        $this->addFlash('success', 'Voiture modifiée avec succès !');
        return $this->redirectToRoute('app_voiture_index');
    }

    return $this->render('voiture/edit.html.twig', [
        'voiture' => $voiture,
        'form' => $formx->createView(),
    ]);
}



//DELETE
#[Route('/delete/{id}', name: 'app_voiture_delete')]
public function delete(Request $request, $id, EntityManagerInterface $entityManager): Response
{
    // Fetch the Reclamation entity by ID
    $voiture = $this->getDoctrine()->getRepository(Voiture::class)->find($id);
    
    // Check if the Reclamation entity exists
    if (!$voiture) {
        throw $this->createNotFoundException('voiture not found');
    }

    // Remove the Reclamation entity
    $entityManager->remove($voiture);
    $entityManager->flush();

    return $this->redirectToRoute('app_voiture_index', [], Response::HTTP_SEE_OTHER);
}

private  function compressImage($source, $destination, $quality) {
    $info = getimagesize($source);
    
    if ($info['mime'] == 'image/jpeg') {
        $image = imagecreatefromjpeg($source);
    } elseif ($info['mime'] == 'image/png') {
        $image = imagecreatefrompng($source);
    } elseif ($info['mime'] == 'image/gif') {
        $image = imagecreatefromgif($source);
    } else {
        return false;
    }    // Sauvegarder l'image compressée
       imagejpeg($image, $destination, $quality);
       
       // Libérer la mémoire
       imagedestroy($image);
       
       return true;
   }
   

}