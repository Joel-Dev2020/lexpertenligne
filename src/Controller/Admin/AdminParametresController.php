<?php

namespace App\Controller\Admin;

use App\Entity\Parametres;
use App\Form\ParametresType;
use App\Managers\ParametresManagers;
use Doctrine\ORM\EntityManagerInterface;
use MercurySeries\FlashyBundle\FlashyNotifier;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security as Security;

/**
 * @Route("/admins/parametres", schemes={"https"})
 * @Security("is_granted('ROLE_SUPER_ADMIN') or is_granted('ROLE_ADMIN')")
 */
class AdminParametresController extends AbstractController
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
     * @var ParametresManagers
     */
    private $parametresManagers;

    public function __construct(FlashyNotifier $flashy, EntityManagerInterface $em, ParametresManagers $parametresManagers)
    {
        $this->em = $em;
        $this->flashy = $flashy;
        $this->parametresManagers = $parametresManagers;
    }

    /**
     * @Route("/application/create", name="admin.structre.create")
     * @return Response
     */
    public function new(){
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $structure = new Parametres();
        $this->em->persist($structure);
        $this->em->flush();
        $this->flashy->success('Vous pouvez à présent renseigner vos donnée concenant votre structure.');
        return $this->redirectToRoute('admin.structre.edit', ['id' => $structure->getId()]);
    }

    /**
     * @Route("/edit/{id}", name="admin.structre.edit", methods="GET|POST")
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
        $structures = $this->em->getRepository(Parametres::class)->find($id);
        if(!$structures){
            $structures = new Parametres();
            $this->em->persist($structures);
        }
        $form = $this->createForm(ParametresType::class, $structures);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $this->parametresManagers->editParametres($structures);
            $this->flashy->success('Information(s) mises à jour(s) avec succès.');
            $this->addFlash('success','Information(s) mises à jour(s) avec succès.');
            return $this->redirectToRoute('admin.structre.edit', ['id' => $id]);
        }
        return $this->render('admin/parametres/applications/index.html.twig', [
            'structure' => $structures,
            'form' => $form->createView(),
            'title' => 'Paramètres de l\'application',
            'current_page' => 'parametres_edit',
            'current_global' => 'parametres',
        ]);
    }
}
