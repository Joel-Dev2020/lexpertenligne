<?php

namespace App\Security;

use App\Controller\Security\SecurityController;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Http\Authenticator\AbstractAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\CsrfTokenBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\RememberMeBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Credentials\PasswordCredentials;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Authenticator\Passport\PassportInterface;

class CustomAuthenticator extends AbstractAuthenticator
{
    /**
     * @var UserRepository
     */
    private $userRepository;
    /**
     * @var UrlGeneratorInterface
     */
    private $urlGenerator;
    /**
     * @var EntityManagerInterface
     */
    private $em;

    public function __construct(EntityManagerInterface $em, UserRepository $userRepository, UrlGeneratorInterface $urlGenerator)
    {
        $this->userRepository = $userRepository;
        $this->urlGenerator = $urlGenerator;
        $this->em = $em;
    }

    public function supports(Request $request): ?bool
    {
        return 'security_login' === $request->attributes->get('_route')
            && $request->isMethod('POST');
    }

    public function authenticate(Request $request): PassportInterface
    {
        $user = $this->userRepository->findOneByEmail($request->request->get('email'));
        $request->getSession()->set(SecurityController::LAST_EMAIL, $request->request->get('email'));
        if (null === $user) {
            throw new CustomUserMessageAuthenticationException('Invalid credentials');
        }

        return new Passport($user, new PasswordCredentials($request->request->get('password')), [
            new CsrfTokenBadge('authenticate', $request->request->get('_csrf_token')),
            new RememberMeBadge,
            /*new PasswordUpgradeBadge($request->request->get('password'), $this->userRepository)*/
        ]);
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        /*if ($targetPath = $this->getTargetPath($request->getSession(), $providerKey)) {
            return new RedirectResponse($targetPath);
        }*/
        $request->getSession()->remove(SecurityController::LAST_EMAIL);
        $Roles = $token->getUser()->getRoles();
        $user = $token->getUser();

        // Update your field here.
        $user->setLastLogin(new \DateTime());

        // Persist the data to database.
        $this->em->persist($user);
        $this->em->flush();

        $referer = $request->headers->get('referer');
        if (in_array("ROLE_SUPER_ADMIN", $Roles) || in_array("ROLE_ADMIN", $Roles)) {
            //return new RedirectResponse($this->urlGenerator->generate('admin.home'));
            return new RedirectResponse($referer);
        }else{
            return new RedirectResponse($referer);
        }
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): ?Response
    {
        $data = [
            // you may want to customize or obfuscate the message first
            'error' => strtr($exception->getMessageKey(), $exception->getMessageData())

            // or to translate this message
            // $this->translator->trans($exception->getMessageKey(), $exception->getMessageData())
        ];

        $request->getSession()->set(
            Security::AUTHENTICATION_ERROR,
            $data['error']
        );

        return new RedirectResponse($this->urlGenerator->generate('security_login'));
    }
}
