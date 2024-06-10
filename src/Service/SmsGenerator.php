<?php
namespace App\Service;

use Twilio\Rest\Client;

class SmsGenerator
{
    
    
          public function SendSms(string $number, string $name, string $text)
    {
        
        $accountSid ='ACd411a1b330f9b8d6c148c415ee1a4f3d';  //Identifiant du compte twilio
        $authToken ='95e3ae77cc9c4539bc828161c3504f45'; //Token d'authentification
        $fromNumber ='+19292961575'; // Numéro de test d'envoie sms offert par twilio

        $toNumber = $number; // Le numéro de la personne qui reçoit le message
        $message = ''.$name.' vous a envoyé le message suivant:'.' '.$text.''; //Contruction du sms

        //Client Twilio pour la création et l'envoie du sms
        $client = new Client($accountSid, $authToken);

        $client->messages->create(
            $toNumber,
            [
                'from' => $fromNumber,
                'body' => $message,
            ]
        );


    }




}