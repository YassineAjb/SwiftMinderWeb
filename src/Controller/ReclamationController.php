<?php

namespace App\Controller;

use App\Entity\Reclamation;
use App\Form\ReclamationType;
use App\Repository\ReclamationRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Twilio\Rest\Client;

#[Route('/reclamation')]
class ReclamationController extends AbstractController
{
  
   
    #[Route('/', name: 'app_reclamation_index', methods: ['GET'])]
    public function index(ReclamationRepository $reclamationRepository, SessionInterface $sessionInterface): Response
    {
        if (!UserController::hasPermissionRole($sessionInterface, "admin")) {
            return $this->redirectToRoute('app_user_login');
        }
        return $this->render('reclamation/index.html.twig', [
            'reclamations' => $reclamationRepository->findAll(),
            'loggedIn' => true,

        ]);
    }
    #[Route('/stats_data',name:'static_data',methods:['GET'])]
    public function getData(ReclamationRepository $reclamationRepository)
        {
            $stats=$reclamationRepository->findReclamationByResponse();

            $traiteCount = 0;
            $nonTraiteCount = 0;
           foreach ($stats as $stat) {
            if ($stat['etat']) {
                $traiteCount = $stat['count'];
            } else {
                $nonTraiteCount = $stat['count'];
            }
        }
            $jsonData = [
                'traite' => $traiteCount,
                'non_traite' => $nonTraiteCount
            ];
            return new JsonResponse($jsonData);

}
    #[Route('/stats', name: 'app_reclamation_stat', methods: ['GET'])]
    public function stats( SessionInterface $sessionInterface): Response
    {
        if (!UserController::hasPermissionRole($sessionInterface, "admin")) {
            return $this->redirectToRoute('app_user_login');
        }
        return $this->render('reclamation/stats.html.twig', [
            'loggedIn' => true,

        ]);
    }
    #[Route('/me', name: 'app_reclamation_me', methods: ['GET', 'POST'])]
    public function me(ReclamationRepository $reclamationRepository, SessionInterface $sessionInterface): Response
    {
        $user = UserController::getUserData($sessionInterface);
        if ($user == null) {
            return $this->redirectToRoute("app_user_login");
        }
        return $this->render('reclamation/mesReclamation.htm.twig', [
            'reclamations' => $reclamationRepository->findBy(["user" => $user->getIduser()]),
            'loggedIn' => true,

        ]);
    }
    #[Route('/me/{idreclamation}', name: 'app_reclamation_show_client', methods: ['GET'])]
    public function showMyReclamation(Reclamation $reclamation, SessionInterface $sessionInterface): Response
    {
        $user = UserController::getUserData($sessionInterface);
        if ($user == null) {
            return $this->redirectToRoute("app_user_login");
        }
        return $this->render('reclamation/show_client.html.twig', [
            'reclamation' => $reclamation,
            'loggedIn' => true,

        ]);
    }
    #[Route('/new', name: 'app_reclamation_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, UserRepository $userRepository, SessionInterface $sessionInterface): Response
    {
        $user = UserController::getUserData($sessionInterface);
        if ($user == null) {
            return $this->redirectToRoute("app_user_login");
        }
        $reclamation = new Reclamation();
        $form = $this->createForm(ReclamationType::class, $reclamation);
        $form->handleRequest($request);

        $user = UserController::getUserData($sessionInterface);
        $userFromDataBase = $userRepository->find($user->getIduser());

        if ($form->isSubmitted() && $form->isValid()) {
            $reclamation->setEtat(false);
            $reclamation->setUser($userFromDataBase);
            $entityManager->persist($reclamation);
            $entityManager->flush();

            if (UserController::hasPermissionRole($sessionInterface, "admin")) {
                return $this->redirectToRoute('app_reclamation_index', [], Response::HTTP_SEE_OTHER);
            } else {
                return  $this->redirectToRoute("app_reclamation_me");
            }
        }

        return $this->renderForm('reclamation/new.html.twig', [
            'reclamation' => $reclamation,
            'form' => $form,
            'loggedIn' => true,

        ]);
    }

    #[Route('/{idreclamation}', name: 'app_reclamation_show_admin', methods: ['GET','POST'])]
    public function show(Reclamation $reclamation, SessionInterface $sessionInterface,Request $request, EntityManagerInterface $entityManager,): Response
    {
        if (!UserController::hasPermissionRole($sessionInterface, "admin")) {
            return $this->redirectToRoute('app_user_login');
        }
        if ($request->isMethod('POST')) {
           $message=$request->request->get('message');
           $accountSID="ACdb9b90f6db0d633c0e31508dfac7668a";
           $authToken="32dae5ceb52594abb9ed9577a0354ad6";
           $phoneNumber="+13345390671";
           $client = new Client($accountSID, $authToken);
           $client->messages->create(
            "+21653099828",
            [
                'from' => $phoneNumber,
                'body' => $message
            ]);
            $reclamation->setEtat(true);
            $entityManager->flush();

        }
        return $this->render('reclamation/show.html.twig', [
            'reclamation' => $reclamation,
            'loggedIn' => true,

        ]);
    }   
    

    #[Route('/{idreclamation}/edit', name: 'app_reclamation_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Reclamation $reclamation, EntityManagerInterface $entityManager,SessionInterface $sessionInterface): Response
    {
        $form = $this->createForm(ReclamationType::class, $reclamation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            if(UserController::hasPermissionRole($sessionInterface,"admin")){
                return $this->redirectToRoute('app_reclamation_index', [], Response::HTTP_SEE_OTHER);
            }else{
                return  $this->redirectToRoute("app_reclamation_me");
            }
        }

        return $this->renderForm('reclamation/edit.html.twig', [
            'reclamation' => $reclamation,
            'form' => $form,
            'loggedIn' => true,

        ]);
    }

    #[Route('/{idreclamation}', name: 'app_reclamation_delete', methods: ['POST'])]
    public function delete(Request $request, Reclamation $reclamation, EntityManagerInterface $entityManager, SessionInterface $sessionInterface): Response
    {
        if ($this->isCsrfTokenValid('delete' . $reclamation->getIdreclamation(), $request->request->get('_token'))) {
            $entityManager->remove($reclamation);
            $entityManager->flush();
        }

        if (UserController::hasPermissionRole($sessionInterface, "admin")) {
            return $this->redirectToRoute('app_reclamation_index', [], Response::HTTP_SEE_OTHER);
        } else {
            return  $this->redirectToRoute("app_reclamation_me");
        }
    }
}
