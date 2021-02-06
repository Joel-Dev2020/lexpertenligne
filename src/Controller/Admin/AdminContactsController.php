<?php

namespace App\Controller\Admin;

use App\Entity\Contacts;
use App\Form\ContactsType;
use App\Managers\ContactsManagers;
use App\Repository\ContactsRepository;
use MercurySeries\FlashyBundle\FlashyNotifier;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security as Security;

/**
 * @Route("/admins/contacts", schemes={"https"})
 * @Security("is_granted('ROLE_SUPER_ADMIN') or is_granted('ROLE_ADMIN')")
 */
class AdminContactsController extends AbstractController
{
    /**
     * @var ContactsManagers
     */
    private $contactsManagers;

    /**
     * @var FlashyNotifier
     */
    private $flashy;

    public function __construct(FlashyNotifier $flashy, ContactsManagers $contactsManagers)
    {
        $this->flashy = $flashy;
        $this->contactsManagers = $contactsManagers;
    }

    /**
     * @Route("/", name="admin.contacts.index", methods={"GET"})
     * @param ContactsRepository $contactsRepository
     * @return Response
     */
    public function index(ContactsRepository $contactsRepository): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $contact = new Contacts();
        $form = $this->createForm(ContactsType::class, $contact);
        return $this->render('admin/contacts/index.html.twig', [
            'contacts' => $contactsRepository->findBy([], ['id' => 'DESC']),
            'form' => $form->createView(),
            'title' => 'Liste des contacts',
            'current_page' => 'contacts',
            'current_global' => 'contacts'
        ]);
    }

    /**
     * @Route("/activer/{id}", name="admin.contacts.activer")
     * @param Contacts $contact
     * @return Response
     */
    public function activer(Contacts $contact): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $this->contactsManagers->active($contact);
        $this->flashy->success("Contact modifié avec succès.");
        return $this->redirectToRoute('admin.contacts.index');
    }

    /**
     * @Route("/{id}", name="admin.contacts.show", methods={"GET"})
     * @param Contacts $contact
     * @return Response
     */
    public function show(Contacts $contact): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        return $this->render('admin/contacts/show.html.twig', [
            'contact' => $contact,
            'title' => 'Détails contact',
            'libelle_liste' => 'Liste des contacts',
            'current_page' => 'contacts',
            'current_global' => 'contacts'
        ]);
    }

    /**
     * @Route("/{id}", name="admin.contacts.delete", methods={"DELETE"})
     * @param Request $request
     * @param Contacts $contact
     * @return Response
     */
    public function delete(Request $request, Contacts $contact): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        if ($this->isCsrfTokenValid('delete'.$contact->getId(), $request->request->get('_token'))) {
            $this->contactsManagers->delete($contact);
            $this->flashy->success('Contact supprimé avec succès.');
        }

        return $this->redirectToRoute('admin.contacts.index');
    }
}
