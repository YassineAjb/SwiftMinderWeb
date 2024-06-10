<?php

namespace App\Controller;

use App\Entity\Commande;
use App\Form\CommandeType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Produit;
use App\Service\PdfService;
use Stripe\Stripe;
use Stripe\Checkout\Session;
use Stripe\Exception\ApiErrorException;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Stripe\PaymentIntent;
use Dompdf\Dompdf;
use Dompdf\Options;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\Session\SessionInterface;



class CommandeController extends AbstractController
{
    #[Route('/commande', name: 'app_commande')]
    public function index(): Response
    {

        $Commande = $this->getDoctrine()->getManager()->getRepository(Commande::class)->findAll();

        return $this->render('commande/index.html.twig', [
            'commande' => $Commande
        ]);
    }
    #[Route('/ajoutcommande/{id}', name: 'ajoutcommande')]
    public function ajoutCommande(Request $request, Produit $produit, SessionInterface $session): Response
    {
        $userData = $session->get('user');
        $user = unserialize($userData);
        if (!$userData) {
            return $this->redirectToRoute('app_user_login');
        }
        
        $entityManager = $this->getDoctrine()->getManager();

        // Récupérer le produit depuis la base de données
        $produit = $entityManager->getRepository(Produit::class)->find($produit->getIdproduit());
        

        // Créer une nouvelle instance de Commande
        $commande = new Commande();
        $commande->setIduser($user->getIdUser());
        $commande->setQuantite(1);
        $commande->setSomme($produit->getPrixproduit());
        $commande->setIdproduit($produit);

        // Vérifier si une commande existante pour ce produit existe déjà
        $existingCommande = $entityManager->getRepository(Commande::class)->findOneBy([
            'idproduit' => $produit->getIdproduit()
        ]);

        if ($existingCommande) {
            // Si une commande existe déjà, mettre à jour la quantité et la somme

            $existingCommande->setQuantite($existingCommande->getQuantite() + 1);
        } else {
            // Sinon, persister la nouvelle commande
            $entityManager->persist($commande);
        }

        // Persister les changements dans la base de données
        $entityManager->flush();

        // Rediriger vers la page de commande
        return $this->redirectToRoute('app_commande');
    }

    #[Route('/supprimercommande/{id}', name: 'supprimercommande')]
    public function supprimerCommande(Request $request, Commande $commande): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($commande);
        $entityManager->flush();
        return $this->redirectToRoute('app_commande');
    }
    #[Route('/paiement', name: 'paiement')]

    public function paiement(Request $request): Response
    {
        // Configurez Stripe avec votre clé secrète
        Stripe::setApiKey('sk_test_51P9xooKS9crHgin9f4XHcMNvw2rdfrsvDCtgEsZ7IPdsSdr89vXxMKTb3hKJNivarJjg9rQe1gd9tHdOr2X40mNi000NQZSzOf');

        // Récupérer toutes les commandes
        $commandes = $this->getDoctrine()->getRepository(Commande::class)->findAll();

        // Calculer le montant total à payer
        $total = 0;
        foreach ($commandes as $commande) {
            $total += $commande->getSomme() * $commande->getQuantite();
        }

        try {
            // Créer un paiement avec Stripe
            $paymentIntent = PaymentIntent::create([
                'amount' =>  $total * 100,
                'currency' => 'eur',

            ]);

            if ($paymentIntent === null || !isset($paymentIntent->client_secret)) {
                throw new \Exception('Une erreur s\'est produite lors de la création du paiement.');
            }


            return $this->render('paiement/paiement.html.twig', [
                'client_secret' => $paymentIntent->client_secret,
            ]);
        } catch (\Exception $e) {
            echo $e->getMessage();
            // Gérer les erreurs de paiement
            return $this->render('erreur_paiement.html.twig', [
                'message' => $e->getMessage() ?: 'Une erreur s\'est produite lors du paiement. Veuillez réessayer plus tard.'
            ]);
        }
    }



    #[Route('/paiement/reussi', name: 'paiement_reussi')]
    public function paiementReussi(): Response
    {
        $entityManager = $this->getDoctrine()->getManager();

        // Récupérer 
        $commandes = $this->getDoctrine()->getRepository(Commande::class)->findAll();

        if (!empty($commandes)) {
            foreach ($commandes as $commande) {
                $produit = $commande->getIdproduit();
                $produit->setQauntiteproduit($produit->getQauntiteproduit() - $commande->getQuantite());
            }
            $entityManager->flush();

            // Créer une nouvelle instance de Dompdf
            $pdfOptions = new Options();
            $pdfOptions->set('defaultFont', 'Garamond');
            $domPdf = new Dompdf($pdfOptions);

            // Générer le HTML à partir du modèle Twig
            $html = $this->renderView('commande/pdf.html.twig', [
                'commandes' => $commandes
            ]);

            // Charger le HTML dans Dompdf
            $domPdf->loadHtml($html);
            $domPdf->setPaper('A4', 'portrait');
            $domPdf->render();

            // Récupérer le contenu du PDF
            $pdfContent = $domPdf->output();

            // Créer une réponse HTTP avec le contenu du PDF
            $response = new Response($pdfContent);

            // Configuration de l'en-tête HTTP pour le téléchargement du fichier
            $response->headers->set('Content-Type', 'application/pdf');
            $response->headers->set('Content-Disposition', 'attachment; filename="details.pdf"');

            // Enregistrer la réponse HTTP
            return $response;
        }

        return $this->render('paiement/reussi.html.twig');
    }

    #[Route('/paiement/annule', name: 'paiement_annule')]
    public function paiementAnnule(): Response
    {
        return $this->render('paiement/annule.html.twig');
    }
    /**
     * @Route("/update_quantity/{id}", name="update_quantity", methods={"POST"})
     */
    public function updateQuantity(Request $request, Commande $commande): Response
    {
        $quantite = $request->request->get('quantite');

        $commande->setQuantite($quantite);

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->flush();

        return new Response('Quantité mise à jour avec succès', Response::HTTP_OK);
    }
    #[Route('/Pdf', name: 'genererPDF')]
    public function generatePDF(PdfService $pdf)
    {
        // Récupérer toutes les commandes depuis la base de données
        $commandes = $this->getDoctrine()->getManager()->getRepository(Commande::class)->findAll();

        // Créer une nouvelle instance de Dompdf
        $pdfOptions = new Options();
        $pdfOptions->set('defaultFont', 'Garamond');
        $domPdf = new Dompdf($pdfOptions);

        // Générer le HTML à partir du modèle Twig
        $html = $this->renderView('commande/pdf.html.twig', [
            'commandes' => $commandes
        ]);

        // Charger le HTML dans Dompdf
        $domPdf->loadHtml($html);
        $domPdf->setPaper('A4', 'portrait');
        $domPdf->render();

        // Récupérer le contenu du PDF
        $pdfContent = $domPdf->output();

        // Créer une réponse HTTP avec le contenu du PDF
        $response = new Response($pdfContent);

        // Configuration de l'en-tête HTTP pour le téléchargement du fichier
        $response->headers->set('Content-Type', 'application/pdf');
        $response->headers->set('Content-Disposition', 'attachment; filename="details.pdf"');

        // Enregistrer la réponse HTTP
        $response->send();


        return $this->redirectToRoute('paiement_reussi');
    }
}