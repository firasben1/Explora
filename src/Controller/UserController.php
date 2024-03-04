<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserClientType;
use App\Form\UserType;
use App\Repository\UserRepository;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;



#[Route('/user')]
class UserController extends AbstractController
{
    #[Route('/user/profil', name: 'app_user_profil')]
    public function index(): Response
    {
        return $this->render('user/userinterface.html.twig', [
        ]);
    }

    #[Route('/new', name: 'app_user_new', methods: ['GET', 'POST'])]
    public function new(Request $request, UserRepository $userRepository,UserPasswordHasherInterface $encodage): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setRoles(['ROLE_ADMIN']);
            $passworld =$encodage->hashPassword($user, $user->getPassword());
            $user->setPassword($passworld);
            $userRepository->save($user, true);

            return $this->redirectToRoute('app_user_show', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('user/new.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    
    #[Route('/', name: 'app_user_show', methods: ['GET'])]
    public function show( ManagerRegistry $doctrine): Response
    {
        $userRepository=$doctrine->getRepository(User::class);
        $user=$userRepository->findAll();
        return $this->render('user/index.html.twig', [
            'user' => $user
            
        ]);
    }

    #[Route('/delete/{id}', name: 'app_user_delete')]
    public function delete(Request $request, User $user, UserRepository $userRepository): Response
    {
        
            $userRepository->remove($user, true);
        

        return $this->redirectToRoute('app_user_show', [], Response::HTTP_SEE_OTHER);
    }
    
    #[Route('/edit/user', name: 'app_user_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, UserRepository $userRepository,UserPasswordHasherInterface $encodage): Response
    {
       // $user=$sec->getUser();

        $user=$userRepository->findOneBy(['email'=>$this->getUser()->getUserIdentifier()]);
        $form = $this->createForm(UserClientType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $passworld =$encodage->hashPassword($user, $user->getPassword());
            $user->setPassword($passworld);
            $userRepository->save($user, true);
        
            return $this->redirectToRoute('app_user_profil', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('user/edit.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }
    #[Route('/users/filter', name: 'filter_users')]
public function filterUsers(Request $request, UserRepository $userRepository): Response
{
    $role = $request->query->get('roles', 'ROLE_ADMIN'); // Get the 'role' parameter from the query string

    // Use a modified version of findByRole that returns a QueryBuilder
    $queryBuilder = $userRepository->createQueryBuilderByRole($role);

    // Fetch the results from the query builder
    $filteredUsers = $queryBuilder->getQuery()->getResult();

    return $this->render('user/index.html.twig', [
        'users' => $filteredUsers,
    ]);
}


  
}