<?php

namespace App\Controller;

use App\Entity\Candidat;
use App\Entity\Evenement;

use App\Form\CandidatType;
use App\Repository\CandidatRepository;
use App\Repository\EvenementRepository;
use Knp\Component\Pager\PaginatorInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/candidat')]
class CandidatController extends AbstractController
{
    #[Route('/{id_event}', name: 'app_candidat_index', methods: ['GET'])]
    public function index(CandidatRepository $candidatRepository, int $id_event): Response
{
    // Fetch candidates associated with the provided event ID
    $candidats = $id_event ? $candidatRepository->findBy(['idelection' => $id_event]) : $candidatRepository->findAll();

    return $this->render('candidat/index.html.twig', [
        'candidats' => $candidats,
        'id_event' => $id_event,

    ]);
}



#[Route('/front/{id_event}', name: 'app_candidat_index_front', methods: ['GET'])]
public function indexFront(CandidatRepository $candidatRepository,EvenementRepository $evenementRepository ,PaginatorInterface $paginator ,
Request $request, int $id_event): Response

{
    $candidats = $id_event ? $candidatRepository->findBy(['idelection' => $id_event]) : $candidatRepository->findAll();

    $candidats=$paginator->paginate(
        $candidats,
        $request->query->getInt('page',1),
        3
    );

// Fetch the event details based on the provided event ID
$evenement = $evenementRepository->find($id_event);



return $this->render('candidat/listeCondidatFront.html.twig', [
    'candidats' => $candidats,
    'id_event' => $id_event,
    'evenement' => $evenement, 
]);

}
    #[Route('/new/{id_event}', name: 'app_candidat_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, int $id_event): Response
    {
        $candidat = new Candidat();
        $form = $this->createForm(CandidatType::class, $candidat);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            $file = $form->get('imgcpath')->getData();

            if ($file) {
                // Specify the upload directory path directly
                $uploadDirectory = $this->getParameter('kernel.project_dir') . '/public/uploads';
                $fileName = md5(uniqid()) . '.' . $file->guessExtension();
                $file->move($uploadDirectory, $fileName);
                $candidat->setImgcpath($fileName);
            }
            // Set the idelection property of the candidat entity
            $evenement = $entityManager->getRepository(Evenement::class)->find($id_event);
            $candidat->setIdelection($evenement);
    
            $entityManager->persist($candidat);
            $entityManager->flush();
    
            return $this->redirectToRoute('app_mailer', ['id_event' => $id_event], Response::HTTP_SEE_OTHER);
        }
    
        return $this->renderForm('candidat/new.html.twig', [
            'candidat' => $candidat,
            'form' => $form,
            'id_event' => $id_event,
        ]);
    }


    #[Route('/{idc}', name: 'app_candidat_show', methods: ['GET'])]
    public function show(Candidat $candidat): Response
    {
        return $this->render('candidat/show.html.twig', [
            'candidat' => $candidat,
        ]);
    }

    #[Route('/{idc}/{id_event}/edit', name: 'app_candidat_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Candidat $candidat, EntityManagerInterface $entityManager, int $id_event): Response
    {
        $form = $this->createForm(CandidatType::class, $candidat);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            // Handle image upload
            $imageFile = $form->get('imgcpath')->getData();
            $file = $form->get('imgcpath')->getData();

            if ($file) {
                // Specify the upload directory path directly
                $uploadDirectory = $this->getParameter('kernel.project_dir') . '/public/uploads';
                $fileName = md5(uniqid()) . '.' . $file->guessExtension();
                $file->move($uploadDirectory, $fileName);
                $candidat->setImgcpath($fileName);
            }
    
            $entityManager->flush();
    
            return $this->redirectToRoute('app_candidat_index', ['id_event' => $id_event], Response::HTTP_SEE_OTHER);
        }
    
        return $this->renderForm('candidat/edit.html.twig', [
            'candidat' => $candidat,
            'form' => $form,
            'id_event' => $id_event,
        ]);
    }
    

    #[Route('/{idc}/delete/{id_event}', name: 'app_candidat_delete', methods: ['POST'])]
    public function delete(Request $request, Candidat $candidat, EntityManagerInterface $entityManager , int $id_event): Response
    {
        if ($this->isCsrfTokenValid('delete'.$candidat->getIdc(), $request->request->get('_token'))) {
            $entityManager->remove($candidat);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_candidat_index', ['id_event' => $id_event], Response::HTTP_SEE_OTHER);
    }
}
