<?php

namespace App\Controller\Security;

use App\Entity\User;
use App\Form\UserRegistrationType;
use App\Managers\UserManagers;
use App\Security\CustomAuthenticator;
use MercurySeries\FlashyBundle\FlashyNotifier;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * @Route("/security", schemes={"https"})
 */
class SecurityController extends AbstractController
{
    public const LAST_EMAIL = 'app_login_form_last_email';

    /**
     * @var FlashyNotifier
     */
    private $flashy;
    /**
     * @var UserManagers
     */
    private $userManagers;

    public function __construct(FlashyNotifier $flashy, ContainerInterface $container, UserManagers $userManagers)
    {
        $this->flashy = $flashy;
        $this->container = $container;
        $this->userManagers = $userManagers;
    }

    /**
     * @Route("/login", name="security_login")
     * @param Request $request
     * @param AuthenticationUtils $authenticationUtils
     * @return Response
     */
    public function login(Request $request, AuthenticationUtils $authenticationUtils): Response
    {
        $referer = $request->headers->get('referer');
        if ($request->isXmlHttpRequest()){
            if ($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY'))
            {
                return $this->redirectToRoute('home');
            }
            // get the login error if there is one
            $error = $authenticationUtils->getLastAuthenticationError();
            // last username entered by the user
            $lastUsername = $authenticationUtils->getLastUsername();
            if (!is_null($error) && $error === 'Invalid credentials.'){
                // last username entered by the user
                $lastUsername = $authenticationUtils->getLastUsername();
                return new JsonResponse([
                    'message' => 'error',
                    'urlRedirect' => $referer,
                ]);
            }
        }else{
            if ($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY'))
            {
                return $this->redirectToRoute('home');
            }
            // get the login error if there is one
            $error = $authenticationUtils->getLastAuthenticationError();
            // last username entered by the user
            $lastUsername = $authenticationUtils->getLastUsername();
            /*$referer = $request->headers->get('referer');
            return $this->redirect($referer);*/
        }

        return $this->render('security/login.html.twig', [
            'title' => 'Connectez vous à votre compte',
            'last_username' => $lastUsername,
            'error' => $error,
            'current_page' => '',
            'current_global' => ''
        ]);
    }

    /**
     * @Route("/register", name="security_register")
     * @param Request $request
     * @param UserPasswordEncoderInterface $encoder
     * @param UserManagers $userManagers
     * @return Response
     */
    public function registration(
        Request $request,
        UserPasswordEncoderInterface $encoder,
        UserManagers $userManagers
    ): Response
    {
        if ($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY'))
        {
            return $this->redirectToRoute('home');
        }

        $user = new User();
        $form = $this->createForm(UserRegistrationType::class, $user);
        $form->handleRequest($request);

        if ($request->isXmlHttpRequest()){
            $userManagers->registerAccount($user);
            if($user){
                $this->flashy->success('Votre inscription a été prise en compte.');
                $this->addFlash('success','Votre inscription a été prise en compte.');
                return new JsonResponse([
                    'message' => 'success',
                    'urlRedirect' => $this->generateUrl('security_login', [], UrlGeneratorInterface::ABSOLUTE_URL),
                ]);
            }else{
                return new JsonResponse([
                    'message' => 'error',
                ]);
            }
        }

        if($form->isSubmitted() && $form->isValid()){
            $userManagers->registerAccount($user);
            $this->flashy->success('Votre inscription a été prise en compte.');
            $this->addFlash('success','Votre inscription a été prise en compte.');
        }

        return $this->render('security/registration.html.twig', [
            'form' => $form->createView(),
            'title' => 'Créer votre compte',
            'last' => $user,
            'current_page' => '',
            'current_global' => ''
        ]);
    }


    /**
     * @Route("/checkout/register", name="security_checkout_register")
     * @param Request $request
     * @param UserPasswordEncoderInterface $encoder
     * @param GuardAuthenticatorHandler $guardHandler
     * @param ContainerInterface $container
     * @param CustomAuthenticator $authenticator
     * @return Response
     */
    public function registrationCheckout(
        Request $request,
        UserPasswordEncoderInterface $encoder,
        GuardAuthenticatorHandler $guardHandler,
        ContainerInterface $container,
        CustomAuthenticator $authenticator
    ): Response
    {
        if ($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY'))
        {
            return $this->redirectToRoute('home');
        }

        $user = new User();
        $form = $this->createForm(UserRegistrationType::class, $user);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $password = $encoder->encodePassword($user, $user->getPassword());
            $user->setPassword($password);
            $user->setEnabled(true);
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();
            $this->flashy->success('Votre inscription a été prise en compte.');
            $this->addFlash('success','Votre inscription a été prise en compte.');
            $token = new UsernamePasswordToken(
                $user,
                $password,
                'main',
                $user->getRoles()
            );
            //Connexion automatique
            $this->get('security.token_storage')->setToken($token);
            $this->get('session')->set('_security_main', serialize($token));
            return $this->redirectToRoute('shop.adresses.index', [], 301);
        }
    }


    /**
     * @Route("/logout", name="security_logout", methods={"GET"})
     */
    public function logout()
    {
        $this->addFlash('success', 'Merci pour votre visite sur la plateforme');
        //throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
        return $this->redirectToRoute('security_login');
    }
}
