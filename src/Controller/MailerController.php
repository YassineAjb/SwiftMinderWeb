<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;

class MailerController extends AbstractController
{
    #[Route('/mailer/{id_event}', name: 'app_mailer')]
    public function index(MailerInterface $mailer,int $id_event): Response
    {
        $email = (new Email())
        ->from('swiftminder2@outlook.com') 
        ->to('yassineajbouni.ya@gmail.com') 
        ->subject('Success')
        ->text('Your condidate has been added .');

        $mailer->send($email);

        return $this->redirectToRoute('app_candidat_index', ['id_event' => $id_event], Response::HTTP_SEE_OTHER);

    }
    
}