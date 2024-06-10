<?php

namespace App\Controller;

use App\Entity\Article;
use App\Form\ArticleType;
use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;
use App\Service\SmsGenerator;
use Doctrine\Persistence\ManagerRegistry;
use Dompdf\Dompdf;
use Dompdf\Options;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

#[Route('/article')]
class ArticleController extends AbstractController
{
    #[Route('/show', name: 'app_article_index', methods: ['GET'])]
    public function index(ArticleRepository $articleRepository, PaginatorInterface $paginator, Request $request): Response
    {
        // Récupérer les articles
        $articles = $articleRepository->findAll(); // Récupérer tous les articles depuis le dépôt

        // Paginer les articles
        $pagination = $paginator->paginate(
            $articles,
            $request->query->getInt('page', 1),
            3// Nombre d'articles par page
        );

        // Passer les articles paginés au template
        return $this->render('article/showArticles.html.twig', [
            'pagination' => $pagination,
        ]);
    
    }

    #[Route('/news', name: 'app_article_news', methods: ['GET'])]
    public function news(ArticleRepository $articleRepository): Response
    {
        return $this->render('article/news.html.twig', [
            'Articles' => $articleRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_article_new', methods: ['GET', 'POST'])]
    public function new(Request $request, SmsGenerator $smsGenerator, EntityManagerInterface $entityManager, SessionInterface $session): Response
    {
        $article = new Article();
        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);
        $userData = $session->get('user');
        $user = unserialize($userData);
        if (!$userData) {
            return $this->redirectToRoute('app_user_login');
        }

        $article->setIdjournaliste($user);

        if ($form->isSubmitted() && $form->isValid()) {
            $file = $form['image']->getData();
            if ($file) {
                // Generate a unique name for the file before saving it
                $fileName = $article->getTitre().'.'.$file->guessExtension();

                // Move the file to the directory where images are stored
                $file->move(
                    $this->getParameter('article_image_directory'),
                    $fileName
                );
                // Set the image path on the entity
                $article->setImage($fileName);
            }
            $entityManager->persist($user);
            $entityManager->persist($article);
            $entityManager->flush();
             // SMS sending logic remains the same
        $name = 'SWIFTMINDER';
        $text = 'Un nouveau Article a été ajouté : ' . $article->getTitre();
        //$smsGenerator->SendSms('+21625506906',$name, $text);
        $smsGenerator->SendSms('+21625506906',$name, $text);
        
            return $this->redirectToRoute('app_article_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('article/newArticle.html.twig', [
            'article' => $article,
            'f' => $form,
        ]);
    }

    

    #[Route('/{idarticle}/edit', name: 'app_article_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Article $article, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_article_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('article/edit.html.twig', [
            'article' => $article,
            'form' => $form,
        ]);
    }

    

    #[Route('/{idarticle}', name: 'app_article_delete')]
    public function delete(ManagerRegistry $manager , ArticleRepository $repo, $idarticle):Response{
        $em = $manager->getManager();

        $Article = $repo->find($idarticle);

        $em->remove($Article);
        $em->flush();
        
        return $this->redirectToRoute('app_article_index');
    }


    #[Route('/article/search', name: 'app_article_search', methods: ['GET'])]
public function search(Request $request, ArticleRepository $articleRepository): Response
{
    $query = $request->query->get('query');
    if ($query) {
        $articles = $articleRepository->createQueryBuilder('f')
            ->where('f.titre LIKE :query')
            ->setParameter('query', '%' . $query . '%')
            ->getQuery()
            ->getResult();
    } else {
        // Si aucune requête de recherche n'est fournie, récupérer tous les articles
        $articles = $articleRepository->findAll();
    }

    // Passer les articles au template
    return $this->render('article/news.html.twig', [
        'Articles' => $articles,
    ]);
}

#[Route('/generate-pdf/{idarticle}', name: 'generate_pdf')]
public function generatePdf($idarticle): Response
{
    // Récupérer l'article depuis la base de données ou tout autre source de données
    $article = $this->getDoctrine()->getRepository(Article::class)->find($idarticle);

    // Créer une instance de Dompdf avec des options facultatives
    $options = new Options();
    $options->set('defaultFont', 'Helvetica');
    $dompdf = new Dompdf($options);

    // Générer le contenu HTML du PDF
    $html = $this->renderView('article/article.html.twig', [
        'article' => $article,
    ]);

    // Charger le contenu HTML dans Dompdf
    $dompdf->loadHtml($html);

    // Rendre le PDF
    $dompdf->render();

    // Obtenez le contenu du PDF généré
    $pdfContent = $dompdf->output();

    // Créer une réponse avec le contenu du PDF
    $response = new Response($pdfContent);

    // Ajouter des en-têtes pour forcer le téléchargement du fichier
    $disposition = $response->headers->makeDisposition(
        ResponseHeaderBag::DISPOSITION_ATTACHMENT,
        'article.pdf'
    );

    $response->headers->set('Content-Type', 'application/pdf');
    $response->headers->set('Content-Disposition', $disposition);

    return $response;
}



    
}