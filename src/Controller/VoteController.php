<?php

namespace App\Controller;

use App\Entity\Vote;
use App\Entity\Candidat;
use App\Entity\Evenement;

use App\Form\VoteType;
use App\Repository\VoteRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/vote')]
class VoteController extends AbstractController
{
    #[Route('/{idc}/{id_event}', name: 'app_vote_index', methods: ['GET'])]
    public function index(int $idc, VoteRepository $voteRepository , int $id_event): Response
    {
        // Fetch votes for the given candidate ID
        $votes = $voteRepository->findBy(['candidat' => $idc]);
    
        return $this->render('vote/index.html.twig', [
            'votes' => $votes,
            'id_event' => $id_event,

        ]);
    }
    #[Route('/', name: 'all_vote_index', methods: ['GET'])]
    public function indexAll( VoteRepository $voteRepository): Response
    {
        $votes = $voteRepository->findAll();
    
        return $this->render('vote/index.html.twig', [
            'votes' => $votes,

        ]);
    }

    #[Route('/new/{id_event}/{idc}', name: 'app_vote_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, int $idc, int $id_event): Response
    {
        // Create a new Vote object
        $vote = new Vote();
        
        // Fetch the Candidat entity based on the provided $idc
        $candidat = $entityManager->getRepository(Candidat::class)->find($idc);
        
        // Fetch the Evenement entity based on the provided $id_event
        $evenement = $entityManager->getRepository(Evenement::class)->find($id_event);
        
        // Set the Candidat object as the candidat property of the Vote object
        $vote->setCandidat($candidat);
        
        // Set the Evenement object as the evenement property of the Vote object
        $vote->setEvenement($evenement);
        
        // Create a form for the Vote object
        $form = $this->createForm(VoteType::class, $vote);
        $form->handleRequest($request);
        
        // Handle form submission
        if ($form->isSubmitted() && $form->isValid()) {
            // Persist the Vote object
            $entityManager->persist($vote);
            $entityManager->flush();
        
            // Redirect to the vote index page
            return $this->redirectToRoute('app_vote_index', ['idc' => $idc , 'id_event' => $id_event]);
        }
        
        // Render the new vote form template
        return $this->render('vote/new.html.twig', [
            'vote' => $vote,
            'form' => $form->createView(),
            'id_event' => $id_event,
            'idc' => $idc,
        ]);
    }
    
    
    #[Route('/front/new/{idc}/{id_event}', name: 'app_vote_new_from_front', methods: ['GET', 'POST'])]
    public function newVoteFromFront(Request $request, EntityManagerInterface $entityManager, int $idc, int $id_event): Response
    {
        // Récupérer le candidat et l'événement
        $candidat = $entityManager->getRepository(Candidat::class)->find($idc);
        $evenement = $entityManager->getRepository(Evenement::class)->find($id_event);
    
        $idUser = 3;

        // Créer un nouveau vote
        $vote = new Vote();
        $vote->setCandidat($candidat);
        $vote->setEvenement($evenement);
        $vote->setIdUser($idUser); // Set the idUser
    
        // Persistez le vote
        $entityManager->persist($vote);
        $entityManager->flush();
    
        // Redirection vers une page de succès ou autre
        // Vous pouvez rediriger où vous le souhaitez
        // return $this->redirectToRoute('app_vote_show', ['id_event' => $id_event]);    
        return $this->redirectToRoute('app_evenement_index');
    }
    
    
    #[Route('/{idv}', name: 'app_vote_show', methods: ['GET'])]
    public function show(Vote $vote): Response
    {
        return $this->render('vote/show.html.twig', [
            'vote' => $vote,
        ]);
    }

    #[Route('/{idv}/edit', name: 'app_vote_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Vote $vote, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(VoteType::class, $vote);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_vote_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('vote/edit.html.twig', [
            'vote' => $vote,
            'form' => $form,
        ]);
    }

    #[Route('/{idv}', name: 'app_vote_delete', methods: ['POST'])]
    public function delete(Request $request, Vote $vote, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$vote->getIdv(), $request->request->get('_token'))) {
            $entityManager->remove($vote);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_vote_index', [], Response::HTTP_SEE_OTHER);
    }
}
