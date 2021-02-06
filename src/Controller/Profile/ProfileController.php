<?php

namespace App\Controller\Profile;

use App\Entity\User;
use App\Form\UserComptesType;
use App\Form\UserEditProfileType;
use App\Managers\UserManagers;
use App\Repository\Shop\ProductsRepository;
use MercurySeries\FlashyBundle\FlashyNotifier;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security as Security;

/**
 * @Route("/profil/{username}", schemes={"https"})
 * @Security("is_granted('ROLE_SUPER_ADMIN') or is_granted('ROLE_ADMIN')  or is_granted('ROLE_USER')")
 */
class ProfileController extends AbstractController
{

    /**
     * @var ProductsRepository
     */
    private $productsRepository;
    /**
     * @var FlashyNotifier
     */
    private $flashy;
    /**
     * @var UserManagers
     */
    private $userManagers;

    /**
     * ProfileController constructor.
     * @param FlashyNotifier $flashy
     * @param UserManagers $userManagers
     * @param ProductsRepository $productsRepository
     */
    public function __construct(
        FlashyNotifier $flashy,
        UserManagers $userManagers,
        ProductsRepository $productsRepository
    )
    {
        $this->productsRepository = $productsRepository;
        $this->flashy = $flashy;
        $this->userManagers = $userManagers;
    }


    /**
     * @Route("/", name="profil.index")
     */
    public function index()
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        return $this->render('web/profil/index.html.twig', [
            'title' => 'Bienvenue '.$this->getUser()->getUsername(),
            'current_page' => 'profilhome',
            'current_global' => 'profils',
        ]);
    }

    /**
     * @Route("/{id}/edit", name="profil.user.edit", methods={"GET","POST"})
     * @param Request $request
     * @param User $user
     * @return Response
     */
    public function edit(Request $request, User $user): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $form = $this->createForm(UserEditProfileType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $this->userManagers->editAccount($user);
            $this->flashy->success("Modification éffectuée.");
            $this->addFlash('success', 'Profil modifié avec succès.');
            return $this->redirectToRoute('profil.index');
        }

        return $this->render('web/profil/edit_compte.html.twig', [
            'title' => 'Modifier vos informations '.$this->getUser()->getUsername(),
            'current_page' => 'profilhome',
            'current_global' => 'profils',
        ]);
    }
}