<?php

namespace App\Controller;

use App\Entity\Category;
use App\Form\CategoryType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

use Symfony\Component\Routing\Annotation\Route;

class CategoryController extends AbstractController
{
    #[Route('/affichagecategorie', name: 'app_category')]
    public function index(): Response
    {
        $category = $this->getDoctrine()->getManager()->getRepository(Category::class)->findAll();

        return $this->render('category/index.html.twig', [
            'b'=>$category
        ]);
    }
    #[Route('/ajoutcategorie', name: 'ajoutcategorie')]
    public function ajoutproduit(Request $request): Response
    {
        $categorie = new Category();
        $form = $this->createForm(CategoryType::class, $categorie);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            
            $entityManager->persist($categorie);
            $entityManager->flush();

            return $this->redirectToRoute('app_category');
        }

        return $this->render('category/ajout.html.twig', ['form' => $form->createView()]);
    }
    #[Route('/modifiercategorie/{id}', name: 'modifiercategorie')]
    public function modifiercategorie(Request $request,$id): Response
    {
        $category = $this->getDoctrine()->getManager()->getRepository(Category::class)->find($id) ;
        $form = $this->createForm(CategoryType::class,$category) ;
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            
            $entityManager->persist($category);
            $entityManager->flush();

            return $this->redirectToRoute('app_category');
        }

        return $this->render('categoey/modif.html.twig',['form'=>$form->createView()]);
    }
    
    #[Route('/supprimercategorie/{id}', name: 'supprimercategorie')]
    public function supprimerCategory(Category $category): Response
    {
        $en = $this->getDoctrine()->getManager();
        $en->remove($category);
        $en->flush();

        return $this->redirectToRoute('app_category');
    }

   
    
}
