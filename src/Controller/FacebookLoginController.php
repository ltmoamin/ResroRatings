<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
use League\OAuth2\Client\Provider\Facebook;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Http\EntryPoint\AuthenticationEntryPointInterface;

class FacebookLoginController extends AbstractController
{
    public $provider;
    public function __construct()
    {
        $this->provider=new Facebook([
            'clientId'          => $_ENV['FCB_ID'],
            'clientSecret'      => $_ENV['FCB_Secret'],
            'redirectUri'       => $_ENV['FCB_Callback'],
            'graphApiVersion'   => 'v16.0',
        ]);
    }

    #[Route('/facebook/fcb-login', name: 'app_facebook_login')]
    public function connectAction(ClientRegistry $clientRegistry)
    {
        // on Symfony 3.3 or lower, $clientRegistry = $this->get('knpu.oauth2.registry');
        // will redirect to Facebook!
        return $clientRegistry
            ->getClient('facebook_main') // key used in config/packages/knpu_oauth2_client.yaml
            ->redirect(
                ['public_profile', 'email'], // Scopes you want to access
                [] // Extra parameters, if needed
            );
    }

    #[Route('/facebook/check', name: 'connect_facebook_check')]
    public function connectCheckAction(Request $request, ClientRegistry $clientRegistry)
    {
        // ** if you want to *authenticate* the user, then
        // leave this method blank and create a custom authenticator
    }

}
