<?php

namespace App\Controller\Admin;

use App\Entity\Publicites;
use App\Form\PublicitesType;
use App\Managers\PublicitesManagers;
use Doctrine\ORM\EntityManagerInterface;
use MercurySeries\FlashyBundle\FlashyNotifier;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security as Security;

/**
 * @Route("/admins/publicites", schemes={"https"})
 * @Security("is_granted('ROLE_SUPER_ADMIN') or is_granted('ROLE_ADMIN')")
 */
class AdminPublicitesController extends AbstractController
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @var FlashyNotifier
     */
    private $flashy;
    /**
     * @var PublicitesManagers
     */
    private $publicitesManagers;

    public function __construct(FlashyNotifier $flashy, EntityManagerInterface $em, PublicitesManagers $publicitesManagers)
    {
        $this->em = $em;
        $this->flashy = $flashy;
        $this->publicitesManagers = $publicitesManagers;
    }

    /**
     * @Route("/application/create", name="admin.publicites.create")
     * @return Response
     */
    public function new(){
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $publicite = new Publicites();
        $this->em->persist($publicite);
        $this->em->flush();
        $this->flashy->success('Vous pouvez à présent renseigner vos données de pubs.');
        return $this->redirectToRoute('admin.publicites.edit', ['id' => $publicite->getId()]);
    }

    /**
     * @Route("/edit/{id}", name="admin.publicites.edit", methods="GET|POST")
     * @param $id
     * @param Request $request
     * @return RedirectResponse|Response
     * @throws \Exception
     */
    public function edit($id, Request $request)
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        if(!$id){
            $id = intval($this->getParameter('company_id'));
        }
        $publicites = $this->em->getRepository(Publicites::class)->find($id);
        if(!$publicites){
            $publicites = new Publicites();
            $this->em->persist($publicites);
        }
        $form = $this->createForm(PublicitesType::class, $publicites);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $this->publicitesManagers->edit($publicites);
            $this->flashy->success('Information(s) mises à jour(s) avec succès.');
            $this->addFlash('success','Information(s) mises à jour(s) avec succès.');
            return $this->redirectToRoute('admin.publicites.edit', ['id' => $id]);
        }
        return $this->render('admin/publicites/index.html.twig', [
            'publicite' => $publicites,
            'form' => $form->createView(),
            'title' => 'Publicités',
            'current_page' => 'publicites_edit',
            'current_global' => 'publicites',
        ]);
    }
}
