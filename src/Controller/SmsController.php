<?php
namespace App\Controller;

use App\Service\TwilioService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Evenement;
use App\Form\EvenementType;
use App\Repository\EvenementRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;

class SmsController extends AbstractController
{
    #[Route('/send-sms', name: 'send_sms')]
    public function sendSms(TwilioService $twilioService): Response
    {
       

        $twilioService->sendSMS();


        return $this->redirectToRoute('app_evenement_index');

    }
}