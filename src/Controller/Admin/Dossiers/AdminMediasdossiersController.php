<?php

namespace App\Controller\Admin\Dossiers;

use App\Entity\Dossiers\Dossiers;
use App\Entity\Dossiers\Mediasdossiers;
use App\Form\Dossiers\MediasdossiersType;
use App\Managers\Dossiers\MediasdossiersManagers;
use App\Repository\Dossiers\DossiersRepository;
use MercurySeries\FlashyBundle\FlashyNotifier;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security as Security;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * @Route("/admins/dossiers/medias", schemes={"https"})
 * @Security("is_granted('ROLE_SUPER_ADMIN') or is_granted('ROLE_ADMIN')")
 */
class AdminMediasdossiersController extends AbstractController
{
    /**
     * @var FlashyNotifier
     */
    private $flashy;
    /**
     * @var MediasdossiersManagers
     */
    private $mediasdossiersManagers;
    /**
     * @var DossiersRepository
     */
    private $dossiersRepository;

    public function __construct(FlashyNotifier $flashy, MediasdossiersManagers $mediasdossiersManagers, DossiersRepository $dossiersRepository)
    {
        $this->flashy = $flashy;
        $this->mediasdossiersManagers = $mediasdossiersManagers;
        $this->dossiersRepository = $dossiersRepository;
    }

    /**
     * @Route("/new", name="admin.mediasdossiers.new", methods={"POST"})
     * @param Request $request
     * @param ValidatorInterface $validator
     * @return Response
     */
    public function new(Request $request, ValidatorInterface $validator): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $mediasdossiers = new Mediasdossiers();
        $form = $this->createForm(MediasdossiersType::class, $mediasdossiers);
        $form->handleRequest($request);
        $dossier_id = intval($form->get('dossier_id')->getData());
        $dossier = $this->dossiersRepository->find($dossier_id);
        $errors = $validator->validate($mediasdossiers);
        if (count($errors) > 0) {
            $this->flashy->error("Votre image est bien trop grande.");
            return $this->redirectToRoute('admin.dossiers.show', ['id' => $dossier->getId()]);
        }
        if ($form->isSubmitted() && $form->isValid()) {
            $this->mediasdossiersManagers->new($mediasdossiers, $dossier);
            $this->flashy->success("Photo ajoutée avec succès.");
            return $this->redirectToRoute('admin.dossiers.show', ['id' => $dossier->getId()]);
        }
        return $this->render('admin/dossiers/show.html.twig', [
            'mediasdossiers' => $mediasdossiers,
            'dossier' => $dossier,
            'title' => 'Détails de la dossier',
            'form' => $form->createView(),
            'libelle_liste' => 'Liste des dossiers',
            'libelle_ajouter' => 'Nouveau dossier',
            'current_page' => 'mediasdossiersposts',
            'current_global' => 'mediasdossiers'
        ]);
    }

    /**
     * @Route("/{id}/{dossier_id}", name="admin.mediasdossiers.delete", methods={"DELETE"})
     * @param Request $request
     * @param Mediasdossiers $mediasdossiers
     * @param $dossier_id
     * @return Response
     */
    public function delete(Request $request, Mediasdossiers $mediasdossiers, $dossier_id): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        if ($this->isCsrfTokenValid('delete'.$mediasdossiers->getId(), $request->request->get('_token'))) {
            $dossier = $this->dossiersRepository->find($dossier_id);
            $this->mediasdossiersManagers->delete($mediasdossiers, $dossier);
            $this->flashy->success("Photo supprimée avec succès.");
        }

        return $this->redirectToRoute('admin.dossiers.show', ['id' => $dossier_id]);
    }
}
