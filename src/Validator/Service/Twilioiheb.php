<?php

namespace App\Service;

use Twilio\Rest\Client;

class Twilioiheb
{
    private $accountSid;
    private $authToken;
    private $twilioPhoneNumber;

    public function __construct()
    {
        $this->accountSid = $_ENV['TWILIO_IHEB_ACCOUNT_SID'];
        $this->authToken = $_ENV['TWILIO_IHEB_AUTH_TOKEN'];
        $this->twilioPhoneNumber = $_ENV['TWILIO_IHEB_PHONE_NUMBER'];
    }

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