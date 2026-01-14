<?php

namespace App\Servicee;

use Twilio\Rest\Client;

class Twilio
{
    private $accountSid = 'AC5cc086fae1b508e887eb9510fb3031b3';
    private $authToken = '061fe614276ed3e37d0a3196f98131f1';
    private $twilioPhoneNumber = '+13139864776';

    public function sendSMS($to, $body)
    {
        $client = new Client($this->accountSid, $this->authToken);
        $client->messages->create(
            $to,
            [
                'from' => $this->twilioPhoneNumber,
                'body' => $body,
            ]
        );
    }
}