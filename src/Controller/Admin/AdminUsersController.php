<?php

namespace App\Controller\Admin;

use App\Entity\User;
use App\Form\UserComptesType;
use App\Form\UserType;
use App\Managers\UserManagers;
use App\Repository\UserRepository;
use Exception;
use Knp\Component\Pager\PaginatorInterface;
use MercurySeries\FlashyBundle\FlashyNotifier;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security as Security;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * @Route("/admins/utilisateurs", schemes={"https"})
 * @Security("is_granted('ROLE_SUPER_ADMIN') or is_granted('ROLE_ADMIN')")
 */
class AdminUsersController extends AbstractController
{
    /**
     * @var FlashyNotifier
     */
    private $flashy;
    /**
     * @var UserManagers
     */
    private $userManagers;
    /**
     * @var PaginatorInterface
     */
    private $paginator;

    /**
     * AdminUsersController constructor.
     * @param FlashyNotifier $flashy
     * @param PaginatorInterface $paginator
     * @param UserManagers $userManagers
     */
    public function __construct(FlashyNotifier $flashy, PaginatorInterface $paginator, UserManagers $userManagers)
    {
        $this->flashy = $flashy;
        $this->userManagers = $userManagers;
        $this->paginator = $paginator;
    }

    /**
     * @Route("/", name="admin.users.index", methods={"GET"})
     * @param Request $request
     * @param UserRepository $userRepository
     * @return Response
     */
    public function index(Request $request, UserRepository $userRepository): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $users = $this->paginator->paginate(
            $userRepository->findAll(), /* query NOT result */
            $request->query->getInt('page', 1)/*page number*/,
            $request->query->getInt('limit', 10)/*limit per page*/
        );
        return $this->render('admin/users/index.html.twig', [
            'users' => $users,
            'title' => 'Liste des utilisateurs',
            'libelle_ajouter' => 'Nouvel utilisateur',
            'current_page' => 'users',
            'current_global' => 'administration'
        ]);
    }

    /**
     * @Route("/activer/{id}", name="admin.users.activer")
     * @param Request $request
     * @param User $user
     * @return Response
     */
    public function activer(Request $request, User $user): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $this->userManagers->activeAccount($user);
        $this->flashy->success("Utilisateur modifié avec succès.");
        $this->addFlash('success', 'Utilisateur modifié avec succès.');
        return $this->redirectToRoute('admin.users.index');
    }

    /**
     * @Route("/new", name="admin.users.new", methods={"GET","POST"})
     * @param Request $request
     * @param UserPasswordEncoderInterface $encoder
     * @return Response
     * @throws Exception
     *
     */
    public function new(Request $request, UserPasswordEncoderInterface $encoder): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->userManagers->registerAccount($user);
            $this->flashy->success("Utilisateur crée avec succès.");
            $this->addFlash('success', 'Utilisateur crée avec succès.');
            return $this->redirectToRoute('admin.users.new');
        }

        return $this->render('admin/users/new.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
            'title' => 'Ajouter un nouvel utilisateur',
            'libelle_liste' => 'Liste des utilisateurs',
            'current_page' => 'users_new',
            'current_global' => 'administration'
        ]);
    }

    /**
     * @Route("/{id}", name="admin.users.show", methods={"GET"})
     * @param User $user
     * @return Response
     */
    public function show(User $user): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        return $this->render('admin/users/show.html.twig', [
            'user' => $user,
            'title' => 'Détails utilisateur',
            'libelle_liste' => 'Liste des utilisateurs',
            'libelle_ajouter' => 'Nouvel utilisateur',
            'current_page' => 'users',
            'current_global' => 'administration'
        ]);
    }

    /**
     * @Route("/{id}/edit", name="admin.users.edit", methods={"GET","POST"})
     * @param Request $request
     * @param User $user
     * @return Response
     * @throws Exception
     */
    public function edit(Request $request, User $user): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $form = $this->createForm(UserComptesType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $this->userManagers->editAccount($user);
            $this->flashy->success("Modification éffectuée.");
            $this->addFlash('success', 'Utilisateur modifié avec succès.');
            return $this->redirectToRoute('admin.users.index', [
                'id' => $user->getId(),
            ]);
        }

        return $this->render('admin/users/edit.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
            'title' => 'Editer un utilisateur',
            'libelle_ajouter' => 'Nouvel utilisateur',
            'libelle_liste' => 'Liste des utilisateurs',
            'current_page' => 'users',
            'current_global' => 'administration'
        ]);
    }

    /**
     * @Route("/{id}", name="admin.users.delete", methods={"DELETE"})
     * @param Request $request
     * @param User $user
     * @return Response
     * @throws Exception
     */
    public function delete(Request $request, User $user): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->request->get('_token'))) {
            $this->userManagers->deleteAccount($user);
            $this->flashy->success("Suppression éffectuée.");
            $this->addFlash('success', 'Utilisateur supprimé avec succès.');
        }

        return $this->redirectToRoute('admin.users.index');
    }
}
