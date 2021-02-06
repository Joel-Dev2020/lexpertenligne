<?php

namespace App\Controller\Admin\Formations;

use App\Entity\Formations\Formations;
use App\Entity\Formations\Mediasformations;
use App\Form\Formations\MediasformationsType;
use App\Managers\Formations\MediasformationsManagers;
use App\Repository\Formations\FormationsRepository;
use MercurySeries\FlashyBundle\FlashyNotifier;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security as Security;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * @Route("/admins/formations/medias", schemes={"https"})
 * @Security("is_granted('ROLE_SUPER_ADMIN') or is_granted('ROLE_ADMIN')")
 */
class AdminMediasformationsController extends AbstractController
{
    /**
     * @var FlashyNotifier
     */
    private $flashy;
    /**
     * @var MediasformationsManagers
     */
    private $mediasformationsManagers;
    /**
     * @var FormationsRepository
     */
    private $formationsRepository;

    public function __construct(FlashyNotifier $flashy, MediasformationsManagers $mediasformationsManagers, FormationsRepository $formationsRepository)
    {
        $this->flashy = $flashy;
        $this->mediasformationsManagers = $mediasformationsManagers;
        $this->formationsRepository = $formationsRepository;
    }

    /**
     * @Route("/new", name="admin.mediasformations.new", methods={"POST"})
     * @param Request $request
     * @param ValidatorInterface $validator
     * @return Response
     */
    public function new(Request $request, ValidatorInterface $validator): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $mediasformations = new Mediasformations();
        $form = $this->createForm(MediasformationsType::class, $mediasformations);
        $form->handleRequest($request);
        $formation_id = intval($form->get('formation_id')->getData());
        $formation = $this->formationsRepository->find($formation_id);
        $errors = $validator->validate($mediasformations);
        if (count($errors) > 0) {
            $this->flashy->error("Votre image est bien trop grande.");
            return $this->redirectToRoute('admin.formations.show', ['id' => $formation->getId()]);
        }
        if ($form->isSubmitted() && $form->isValid()) {
            $this->mediasformationsManagers->new($mediasformations, $formation);
            $this->flashy->success("Photo ajoutée avec succès.");
            return $this->redirectToRoute('admin.formations.show', ['id' => $formation->getId()]);
        }
        return $this->render('admin/formations/show.html.twig', [
            'mediasformations' => $mediasformations,
            'formation' => $formation,
            'title' => 'Détails de la formation',
            'form' => $form->createView(),
            'libelle_liste' => 'Liste des formations',
            'libelle_ajouter' => 'Nouveau formation',
            'current_page' => 'mediasformationsposts',
            'current_global' => 'mediasformations'
        ]);
    }

    /**
     * @Route("/{id}/{formation_id}", name="admin.mediasformations.delete", methods={"DELETE"})
     * @param Request $request
     * @param Mediasformations $mediasformations
     * @param $formation_id
     * @return Response
     */
    public function delete(Request $request, Mediasformations $mediasformations, $formation_id): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        if ($this->isCsrfTokenValid('delete'.$mediasformations->getId(), $request->request->get('_token'))) {
            $formation = $this->formationsRepository->find($formation_id);
            $this->mediasformationsManagers->delete($mediasformations, $formation);
            $this->flashy->success("Photo supprimée avec succès.");
        }

        return $this->redirectToRoute('admin.formations.show', ['id' => $formation_id]);
    }
}
