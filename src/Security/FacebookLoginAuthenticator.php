<?php
namespace App\Security;
use App\Entity\User;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
use League\OAuth2\Client\Provider\FacebookUser;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use KnpU\OAuth2ClientBundle\Security\Authenticator\OAuth2Authenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\SelfValidatingPassport;

class FacebookLoginAuthenticator extends OAuth2Authenticator {
    private ClientRegistry $clientRegistry;
    private EntityManagerInterface $entityManager;
    private RouterInterface $router;
    private UserPasswordHasherInterface $PasswordHasher;

    public function __construct(  ClientRegistry $clientRegistry, EntityManagerInterface $entityManager, RouterInterface $router)
    {
        $this->entityManager=$entityManager;
        $this->clientRegistry=$clientRegistry;
        $this->router=$router;

    }

    public function supports(Request $request): ?bool
    {
        return $request->attributes->get('_route') === 'connect_facebook_check';
    }

    public function authenticate(Request $request )
    {
        $client = $this->clientRegistry->getClient('facebook_main');
        $accessToken = $this->fetchAccessToken($client);
        return new SelfValidatingPassport(
            new UserBadge($accessToken->getToken(), function () use($accessToken,$client){
                $facebookUser=$client->fetchUserFromToken($accessToken);
                $facebookUser=$facebookUser->toArray();
                $email=$facebookUser['email'];
                $user_exist=$this->entityManager->getRepository(User::Class)->findOneByEmail($email);
                if (!$user_exist) {
                    $password=sha1(str_shuffle('abscdol12345690:;.'));
                    $user_exist=new User();
                    $user_exist->setEmail($email);
                    $user_exist->setUsername($facebookUser['name']);
                    $user_exist->setPassword(
                        $this->PasswordHasher->hashPassword(
                            $user_exist,
                            $password
                        ));
                    $date= new DateTimeImmutable();
                    $user_exist->setCreatedAt($date);
                    $this->entityManager->persist($user_exist);
                }
                $this->entityManager->flush();
                return $user_exist;
            })
        );
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        return new RedirectResponse($this->router->generate('home_Front'));
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): ?Response
    {
        $message = strtr($exception->getMessageKey(), $exception->getMessageData());

        return new Response($message, Response::HTTP_FORBIDDEN);
    }
}