<?php

namespace App\Controller;

use App\Entity\Abonnes;
use App\Entity\Contacts;
use App\Form\AbonnesType;
use App\Form\ContactsType;
use App\Interfaces\NotificationsInterface;
use App\Managers\AbonnesManagers;
use App\Managers\ContactsManagers;
use MercurySeries\FlashyBundle\FlashyNotifier;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class ContactsController extends AbstractController
{
    /**
     * @var NotificationsInterface
     */
    private $notifications;
    /**
     * @var AbonnesManagers
     */
    private $abonnesManagers;
    /**
     * @var ContactsManagers
     */
    private $contactsManagers;

    public function __construct(
        NotificationsInterface $notifications,
        AbonnesManagers $abonnesManagers,
        ContactsManagers $contactsManagers
    )
    {
        $this->notifications = $notifications;
        $this->abonnesManagers = $abonnesManagers;
        $this->contactsManagers = $contactsManagers;
    }

    /**
     * @Route("/contact", name="contacts", schemes={"https"})
     * @param Request $request
     * @param SerializerInterface $serializer
     * @param FlashyNotifier $flashy
     * @return JsonResponse|RedirectResponse|Response
     */
    public function index(Request $request, SerializerInterface $serializer, FlashyNotifier $flashy)
    {
        //Traitement du formulaire de contact
        $contact = new Contacts();
        $form = $this->createForm(ContactsType::class, $contact);
        $form->handleRequest($request);
        if ($request->isXmlHttpRequest()){
            $json = $this->json($request->request->get('contacts'));
            /**
             * @var $contact Contacts
             */
            $contact = $serializer->deserialize($json->getContent(), Contacts::class, 'json');

            if($contact->getRobot() !== ""){
                return new JsonResponse(['resultat' => "NONOK"], 301);
            }
            /*$notifications->sendWithDatas($contact);*/
            if ($this->contactsManagers->new($contact) === false){
                return new JsonResponse(['resultat' => "NONOK"], 301);
            }
            return new JsonResponse(['resultat' => "OK"], 200);
        }
        if($form->isSubmitted() && $form->isValid()){
            if($contact->getRobot() !== null){
                return $this->redirectToRoute('contacts');
            }
            /*$notifications->sendWithDatas($contact);*/
            $this->contactsManagers->new($contact);
            $flashy->success('Votre email a bien été envoyé');
            return $this->redirectToRoute('contacts');
        }

        return $this->render('web/contacts/index.html.twig', [
            'title' => 'Contactez nous',
            'contact' => $contact,
            'form' => $form->createView(),
            'current_page' => 'contacts',
            'current_global' => 'contacts',
        ]);
    }

    /**
     * @Route("/abonnes", name="abonnes.index", schemes={"https"})
     * @param Request $request
     * @param SerializerInterface $serializer
     * @param FlashyNotifier $flashy
     * @return JsonResponse|RedirectResponse|Response
     */
    public function abonnes(Request $request,SerializerInterface $serializer, FlashyNotifier $flashy)
    {
        //Traitement du formulaire de contact
        $abonnes = new Abonnes();
        $form = $this->createForm(AbonnesType::class, $abonnes);
        $form->handleRequest($request);
        if ($request->isXmlHttpRequest()){
            $json = $this->json($request->request->get('abonnes'));
            /**
             * @var $abonnes Abonnes
             */
            $abonnes = $serializer->deserialize($json->getContent(), Abonnes::class, 'json');
            $getAbonne = $this->getDoctrine()->getRepository(Abonnes::class)->findOneBy(['email' => $form->get('email')->getData()]);
            if ($getAbonne){
                return new JsonResponse(['resultat' => "email_exist"]);
            }
            $this->abonnesManagers->new($abonnes);
            return new JsonResponse(['resultat' => "OK"]);
        }
        if($form->isSubmitted() && $form->isValid()){
            $getAbonne = $this->getDoctrine()->getRepository(Abonnes::class)->findOneBy(['email' => $form->get('email')->getData()]);
            if ($getAbonne){
                $flashy->error( 'Cet abonné existe déja, veuillez réessayer');
                $this->redirect($request->headers->get('referer'));
            }

            $this->abonnesManagers->new($abonnes);
            $flashy->success( 'Votre email a bien été envoyé');
            $this->redirect($request->headers->get('referer'));
        }
        $this->redirect($request->headers->get('referer'));
    }
}