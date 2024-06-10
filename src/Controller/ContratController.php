<?php

namespace App\Controller;

use App\Entity\Contrat;
use App\Form\ContratType;
use App\Repository\ContratRepository;
use Doctrine\Persistence\ManagerRegistry;
use Dompdf\Dompdf;
use Dompdf\Options;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class ContratController extends AbstractController
{
    #[Route('/Contrat', name: 'app_Contrat')]
    public function index(): Response
    {
        return $this->render('list.html.twig', [
            'controller_name' => 'ContratController',
        ]);
    }

    #[Route('/getContrats', name: 'Contrat_list')]
    public function listContrats(ContratRepository $repo):Response{

        $list = $repo->findAll();
        return $this->render('contrat/listContrat.html.twig',[
            'list' => $list
        ]);
    }

    #[Route('/deleteContrat/{id}', name: 'Contrat_delete')]
    public function deleteContrat(ManagerRegistry $manager , ContratRepository $repo, $id):Response{
        $em = $manager->getManager();

        $Contrat = $repo->find($id);

        $em->remove($Contrat);
        $em->flush();
        
        return $this->redirectToRoute('Contrat_list');
    }

    #[Route('/newContrat', name: 'Contrat_add')]
    public function addContrat(Request $req,ManagerRegistry $manager):Response{

        $em = $manager->getManager();

        $Contrat = new Contrat();

        $form = $this->createForm(ContratType::class,$Contrat);

        $form->handleRequest($req);
        if($form->isSubmitted() && $form->isValid()) {
                $em->persist($Contrat);
                $em->flush();

            return $this->redirectToRoute('Contrat_list');
        }
        return $this->renderForm('contrat/addContrat.html.twig',[
            'f' => $form
        ]);
    }

    #[Route('/EditContrat/{id}', name: 'Contrat_edit')]
    public function editContrat($id, ContratRepository $repo, Request $req,ManagerRegistry $manager):Response{

        $em = $manager->getManager();

        $Contrat = $repo->find($id);

        $form = $this->createForm(ContratType::class,$Contrat);
        
        $form->handleRequest($req);
        if($form->isSubmitted()) {
            $em->persist($Contrat);
            $em->flush();
            return $this->redirectToRoute('Contrat_list');
        }
        return $this->renderForm('Contrat/updateContrat.html.twig',[
            'f' => $form
        ]);
    }


#[Route('/generatePDF/{id}', name: 'Contrat_PDF')]
public function ContratPDF($id, ContratRepository $repo, Request $req)
{
    $pdfOptions = new Options();
    $pdfOptions->set('defaultFont', 'Arial');

    $Contrat = $repo->find($id);

    if (!$Contrat) {
        // Handle case where Contrat is not found (e.g., throw an exception or return a 404 response)
        throw $this->createNotFoundException('Contrat not found');
    }

    // Get the absolute URL of the image
    $imagePath = $this->getParameter('kernel.project_dir').'/public/assets_pdf/swiftminder.png';
    $imageData = $this->imageToBase64($imagePath);

    $dompdf = new Dompdf($pdfOptions);

    $html = $this->renderView('Contrat/ContratJoueur.html.twig', [
        'contrat' => $Contrat,
        'imageData' => $imageData, // Pass the image data to the template
    ]);

    $dompdf->loadHtml($html);
    $dompdf->setPaper('A4', 'portrait');
    $dompdf->render();

    // Set appropriate headers for inline display
    $response = new Response($dompdf->output(), Response::HTTP_OK);
    $response->headers->set('Content-Type', 'application/pdf');
    $response->headers->set('Content-Disposition', 'inline; filename="contrat.pdf"');

    return $response;
}

// Your other controller methods...

// Function to convert image to base64
private function imageToBase64($path) {
    $type = pathinfo($path, PATHINFO_EXTENSION);
    $data = file_get_contents($path);
    $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
    return $base64;
}


}
