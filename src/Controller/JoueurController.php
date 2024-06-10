<?php

namespace App\Controller;

use App\Entity\Joueur;
use App\Form\JoueurType;
use App\Repository\JoueurRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;
use Endroid\QrCode\Builder\BuilderInterface; 
use Endroid\QrCode\Writer\Result\PngResult;

class JoueurController extends AbstractController
{
    private $qrCodeBuilder;

    public function __construct(BuilderInterface $qrCodeBuilder)
    {
        $this->qrCodeBuilder = $qrCodeBuilder;
    }

    private function convertQrCodeResultToString(PngResult $qrCodeResult): string
    {
        $data = 'data:image/png;base64,' . base64_encode($qrCodeResult->getString());
        return $data;
    }

    #[Route('/joueur', name: 'app_joueur')]
    public function index(): Response
    {
        return $this->render('/list.html.twig', [
            'controller_name' => 'JoueurController',
        ]);
    }

    #[Route('/getJoueurs', name: 'Joueur_list')]
    public function listJoueurs(JoueurRepository $repo):Response{

        $list = $repo->findAll();
        foreach ($list as $joueur) {
            if ($this->qrCodeBuilder !== null) {
                $qrCodeResult = $this->qrCodeBuilder
                    ->data($joueur->getLink())
                    ->build();

                $qrCodeString = $this->convertQrCodeResultToString($qrCodeResult);

                $joueur->setQrCode($qrCodeString);
            }
        }
        return $this->render('joueur/listJoueur.html.twig',[
            'list' => $list
        ]);
    }

    #[Route('/Roster', name: 'Joueur_Roster')]
    public function listJoueurs_front(JoueurRepository $repo):Response{

        $list = $repo->findAll();
        foreach ($list as $joueur) {
            if ($this->qrCodeBuilder !== null) {
                $qrCodeResult = $this->qrCodeBuilder
                    ->data($joueur->getLink())
                    ->build();

                $qrCodeString = $this->convertQrCodeResultToString($qrCodeResult);

                $joueur->setQrCode($qrCodeString);
            }
        }
        return $this->render('joueur/Roster.html.twig',[
            'list' => $list
        ]);
    }

    #[Route('/deleteJoueur/{id}', name: 'Joueur_delete')]
    public function deleteJoueur(ManagerRegistry $manager , JoueurRepository $repo, $id):Response{
        $em = $manager->getManager();

        $Joueur = $repo->find($id);

        $em->remove($Joueur);
        $em->flush();
        
        return $this->redirectToRoute('Joueur_list');
    }

    #[Route('/newJoueur', name: 'Joueur_add')]
    public function addJoueur(Request $req,ManagerRegistry $manager):Response{

        $em = $manager->getManager();

        $Joueur = new Joueur();

        $form = $this->createForm(JoueurType::class,$Joueur);

        $form->handleRequest($req);
        if($form->isSubmitted() && $form->isValid()) {

                $file = $form['imagepath']->getData();
            if ($file) {
                $fileName = $Joueur->getId().$Joueur->getNom().'.'.$file->guessExtension();

                $file->move(
                    $this->getParameter('joueurs_image_directory'),
                    $fileName
                );
                $Joueur->setImagepath($fileName);
            }
                $em->persist($Joueur);
                $em->flush();

            return $this->redirectToRoute('Joueur_list');
        }
        return $this->renderForm('Joueur/addJoueur.html.twig',[
            'f' => $form
        ]);
    }

    #[Route('/EditJoueur/{id}', name: 'Joueur_edit')]
    public function editJoueur($id, JoueurRepository $repo, Request $req,ManagerRegistry $manager):Response{

        $em = $manager->getManager();

        $Joueur = $repo->find($id);

        $form = $this->createForm(JoueurType::class,$Joueur);
        
        $form->handleRequest($req);
        if($form->isSubmitted() && $form->isValid()) {
            $file = $form['imagepath']->getData();
            if ($file) {
                $fileName = $Joueur->getId().$Joueur->getNom().'.'.$file->guessExtension();

                $file->move(
                    $this->getParameter('joueurs_image_directory'),
                    $fileName
                );
                $Joueur->setImagepath($fileName);
            }
            $em->persist($Joueur);
            $em->flush();
            return $this->redirectToRoute('Joueur_list');
        }
        return $this->renderForm('joueur/updateJoueur.html.twig',[
            'f' => $form
        ]);
    }

    #[Route('/pageJoueur/{id}', name: 'Joueur_page')]
    public function pageJoueur($id, JoueurRepository $repo):Response{

        $Joueur = $repo->find($id);
        if ($this->qrCodeBuilder !== null) {
                $qrCodeResult = $this->qrCodeBuilder
                    ->data($Joueur->getLink())
                    ->build();

                $qrCodeString = $this->convertQrCodeResultToString($qrCodeResult);

                $Joueur->setQrCode($qrCodeString);
            }

        return $this->renderForm('joueur/pageJoueur.html.twig',[
            'Joueur' => $Joueur
        ]);
    }
}