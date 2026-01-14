<?php

namespace App\Service;

use Twilio\Rest\Client;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class WhatsAppService
{
    private $twilioParams;

    public function __construct(ParameterBagInterface $parameterBag)
    {
        $this->twilioParams = $parameterBag->get('twilio');
    }

    public function sendMessage($recipient, $message)
    {
        $accountSid = 'ACba7b1da31ae028b088a6879190906d68';
        $authToken = 'c3b0c93a6a831d84ded4754ec00bd3e4';
        $twilioNumber = '+13213607562';

        $twilio = new Client($accountSid, $authToken);

        try {
            $message = $twilio->messages
                              ->create("whatsapp:" . $recipient, [
                                  "from" => "whatsapp:" . $twilioNumber,
                                  "body" => $message,
                              ]);
            
            return $message->sid; // Retourne l'ID du message envoyé
        } catch (\Exception $e) {
            // Gérer les erreurs
            return $e->getMessage();
        } 
    }
} 
