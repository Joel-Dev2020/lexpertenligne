<?php

namespace App\Controller\Admin\Shop;

use App\Entity\Shop\Products;
use App\Entity\Shop\Mediasproducts;
use App\Form\Shop\MediasproductsType;
use MercurySeries\FlashyBundle\FlashyNotifier;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security as Security;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * @Route("/admins/products/medias", schemes={"https"})
 * @Security("is_granted('ROLE_SUPER_ADMIN') or is_granted('ROLE_ADMIN')")
 */
class AdminMediasproductsController extends AbstractController
{
    /**
     * @var FlashyNotifier
     */
    private $flashy;

    public function __construct(FlashyNotifier $flashy)
    {
        $this->flashy = $flashy;
    }

    /**
     * @Route("/new", name="admin.mediasproducts.new", methods={"POST"})
     * @param Request $request
     * @param ValidatorInterface $validator
     * @return Response
     */
    public function new(Request $request, ValidatorInterface $validator): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $mediasproducts = new Mediasproducts();
        $form = $this->createForm(MediasproductsType::class, $mediasproducts);
        $form->handleRequest($request);
        $product_id = intval($form->get('product_id')->getData());
        $product = $this->getDoctrine()->getRepository(Products::class)->find($product_id);
        $errors = $validator->validate($mediasproducts);
        if (count($errors) > 0) {
            $this->flashy->error("Votre image est bien trop grande.");
            return $this->redirectToRoute('admin.products.show', ['id' => $product->getId()]);
        }
        if ($form->isSubmitted() && $form->isValid()) {
            $mediasproducts->setProducts($product);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($mediasproducts);
            $entityManager->flush();
            $this->flashy->success("Photo ajoutée avec succès.");
            return $this->redirectToRoute('admin.products.show', ['id' => $product->getId()]);
        }
        return $this->render('admin/shops/products/show.html.twig', [
            'mediasproducts' => $mediasproducts,
            'product' => $product,
            'title' => 'Détails du post',
            'form' => $form->createView(),
            'libelle_liste' => 'Liste des posts',
            'libelle_ajouter' => 'Nouveau post',
            'current_page' => 'mediasproductsposts',
            'current_global' => 'mediasproducts'
        ]);
    }

    /**
     * @Route("/{id}/{product_id}", name="admin.mediasproducts.delete", methods={"DELETE"})
     * @param Request $request
     * @param Mediasproducts $mediasproducts
     * @param $product_id
     * @return Response
     */
    public function delete(Request $request, Mediasproducts $mediasproducts, $product_id): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        if ($this->isCsrfTokenValid('delete'.$mediasproducts->getId(), $request->request->get('_token'))) {
            $product = $this->getDoctrine()->getRepository(Products::class)->find($product_id);
            $mediasproducts->setProducts($product);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($mediasproducts);
            $entityManager->flush();
            $this->flashy->success("Photo supprimée avec succès.");
        }

        return $this->redirectToRoute('admin.products.show', ['id' => $product_id]);
    }
}
