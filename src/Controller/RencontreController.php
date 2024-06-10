<?php

namespace App\Controller;

use App\Entity\Rencontre;
use App\Form\RencontreType;
use App\Repository\RencontreRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/rencontre')]
class RencontreController extends AbstractController
{
    #[Route('/showRencontre', name: 'app_rencontre_index', methods: ['GET'])]
    public function index(RencontreRepository $rencontreRepository): Response
    {
        return $this->render('rencontre/showRencontre.html.twig', [
            'rencontres' => $rencontreRepository->findAll(),
        ]);
    }
    #[Route('/rencontre', name: 'app_rencontre_rencontre', methods: ['GET'])]
    public function news(RencontreRepository $rencontreRepository): Response
    {
        return $this->render('rencontre/rencontre.html.twig', [
            'rencontres' => $rencontreRepository->findAll(),
        ]);
    }

    #[Route('/newRencontre', name: 'app_rencontre_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $rencontre = new Rencontre();
        $form = $this->createForm(RencontreType::class, $rencontre);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($rencontre);
            $entityManager->flush();

            return $this->redirectToRoute('app_rencontre_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('rencontre/newRencontre.html.twig', [
            'rencontre' => $rencontre,
            'f' => $form,
        ]);
    }

    #[Route('/{idrencontre}', name: 'app_rencontre_show', methods: ['GET'])]
    public function show(Rencontre $rencontre): Response
    {
        return $this->render('rencontre/show.html.twig', [
            'rencontre' => $rencontre,
        ]);
    }

    #[Route('/{idrencontre}/edit', name: 'app_rencontre_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Rencontre $rencontre, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(RencontreType::class, $rencontre);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_rencontre_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('rencontre/edit.html.twig', [
            'rencontre' => $rencontre,
            'f' => $form,
        ]);
    } 

    #[Route('/{idrencontre}', name: 'app_rencontre_delete', methods: ['POST'])]
    public function delete(Request $request, Rencontre $rencontre, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$rencontre->getIdrencontre(), $request->request->get('_token'))) {
            $entityManager->remove($rencontre);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_rencontre_index', [], Response::HTTP_SEE_OTHER);
    }
}