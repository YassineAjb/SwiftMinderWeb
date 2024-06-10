<?php

namespace App\Service;

use Twilio\Rest\Client;

class TwilioService
{
    private $accountSid = 'AC5bc580ea6b3b2aee76723258977dfd6a';
    private $authToken = '5e3aadeeec1f3b00fb445bdfd68526ed';
    private $twilioPhoneNumber = '+17744257047';
   
    public function sendSMS()
    {
        $to = '+21623990938'; // Le numéro de téléphone destinataire
        $message = 'you added an election .'; // Le message que vous souhaitez envoyer
        $client = new Client($this->accountSid, $this->authToken);
        $client->messages->create(
            $to,
            [
                'from' => $this->twilioPhoneNumber,
                'body' => $message,
            ]
        );

    }
}
