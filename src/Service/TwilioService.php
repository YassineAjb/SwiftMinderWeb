<?php

namespace App\Service;

use Twilio\Rest\Client;

class TwilioService
{
    private $accountSid = '****';
    private $authToken = '*****';
    private $twilioPhoneNumber = '+****';
   
    public function sendSMS()
    {
        $to = '+****'; // Le numéro de téléphone destinataire
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
