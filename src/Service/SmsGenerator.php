<?php
namespace App\Service;

use Twilio\Rest\Client;

class SmsGenerator
{
    
    
          public function SendSms(string $number, string $name, string $text)
    {
        
        $accountSid ='***';  //Identifiant du compte twilio
        $authToken ='****'; //Token d'authentification
        $fromNumber ='****'; // Numéro de test d'envoie sms offert par twilio

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