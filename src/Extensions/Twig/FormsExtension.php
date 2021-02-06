<?php

namespace App\Extensions\Twig;

use App\Data\SearchData;
use App\Entity\Abonnes;
use App\Entity\Contacts;
use App\Entity\Shop\Mediasproducts;
use App\Entity\User;
use App\Form\AbonnesPopType;
use App\Form\AbonnesType;
use App\Form\AdminSearchFormType;
use App\Form\ContactsType;
use App\Form\NewsletterType;
use App\Form\Shop\SearchFormType;
use App\Form\Shop\MediasproductsType;
use App\Form\Shop\UserRegistrationType;
use App\Form\UserEditProfileType;
use App\Form\UserType;
use Psr\Container\ContainerInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Contracts\Cache\CacheInterface;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class FormsExtension extends AbstractExtension
{
    /**
     * @var Environment
     */
    private $twig;
    /**
     * @var CacheInterface
     */
    private $cache;
    /**
     * @var ContainerInterface
     */
    private $container;
    /**
     * @var RequestStack
     */
    private $requestStack;
    /**
     * @var TokenStorageInterface
     */
    private $context;

    public function __construct(
        Environment $twig,
        CacheInterface $cache,
        RequestStack $requestStack,
        TokenStorageInterface $context,
        ContainerInterface $container
    )
    {
        $this->twig = $twig;
        $this->cache = $cache;
        $this->container = $container;
        $this->requestStack = $requestStack;
        $this->context = $context;
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('contact_form', [$this, 'getContactForm'], ['is_safe' => ['html']]),
            new TwigFunction('newsletter_soon_form', [$this, 'getNewsletterSonForm'], ['is_safe' => ['html']]),
            new TwigFunction('newsletter_pop_form', [$this, 'getNewsletterPopForm'], ['is_safe' => ['html']]),
            new TwigFunction('newsletter_form', [$this, 'getNewsletterForm'], ['is_safe' => ['html']]),
            new TwigFunction('search_form', [$this, 'getSearchForm'], ['is_safe' => ['html']]),
            new TwigFunction('admin_search_form', [$this, 'getAdminSearchForm'], ['is_safe' => ['html']]),
            new TwigFunction('medias_form_products', [$this, 'getMediasFormProducs'], ['is_safe' => ['html']]),
            new TwigFunction('form_login', [$this, 'getFormLogin'], ['is_safe' => ['html']]),
            new TwigFunction('form_register', [$this, 'getFormRegister'], ['is_safe' => ['html']]),
            new TwigFunction('form_edit_profil', [$this, 'getFormEditProfil'], ['is_safe' => ['html']]),
        ];
    }

    public function getFormEditProfil(User $user): string {
        $form = $this->createForm(UserEditProfileType::class, $user);
        return $this->twig->render('pages/profil/partials/__editprofil.html.twig', [
            'form' => $form->createView(),
            'user' => $user,
        ]);
    }

    public function getMediasFormProducs(int $product_id): string {
        $medias = new Mediasproducts();
        $form = $this->createForm(MediasproductsType::class, $medias);
        return $this->twig->render('admin/shops/products/mediasproducts/_form.html.twig', [
            'form' => $form->createView(),
            'id_product' => $product_id,
        ]);
    }

    /**
     * @return string
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function getContactForm(): string {
        $contact = new Contacts();
        $form = $this->createForm(ContactsType::class, $contact);
        return $this->twig->render('forms/__contact_form.html.twig', [
            'form' => $form->createView(),
            'contact' => $contact,
        ]);
    }

    /**
     * @return string
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function getNewsletterSonForm(): string {
        $abonnes = new Abonnes();
        $form = $this->createForm(AbonnesType::class, $abonnes);
        return $this->twig->render('forms/__newsletter.html.twig', [
            'form' => $form->createView(),
            'abonnes' => $abonnes,
        ]);
    }

    /**
     * @return string
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function getNewsletterPopForm(): string {
        $abonnes = new Abonnes();
        $form = $this->createForm(AbonnesPopType::class, $abonnes);
        return $this->twig->render('forms/__abonnes_newsletter_pop.html.twig', [
            'form' => $form->createView(),
            'abonnes' => $abonnes,
        ]);
    }

    /**
     * @return string
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function getNewsletterForm(): string {
        $abonnes = new Abonnes();
        $form = $this->createForm(NewsletterType::class, $abonnes);
        return $this->twig->render('forms/__abonnes_newsletter.html.twig', [
            'form' => $form->createView(),
            'abonnes' => $abonnes,
        ]);
    }

    public function getSearchForm(): string {
        $data = new SearchData();
        $data->page = $this->requestStack->getCurrentRequest()->get('page', 1);
        $form = $this->createForm(SearchFormType::class, $data);
        $form->handleRequest($this->requestStack->getCurrentRequest());
        return $this->twig->render('forms/search_form.html.twig', ['form' => $form->createView(),]);
    }

    public function getAdminSearchForm(): string {
        $data = new SearchData();
        $data->page = $this->requestStack->getCurrentRequest()->get('page', 1);
        $form = $this->createForm(AdminSearchFormType::class, $data);
        $form->handleRequest($this->requestStack->getCurrentRequest());
        return $this->twig->render('forms/admin_search_form.html.twig', ['form' => $form->createView(),]);
    }

    /**
     * @return string
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function getFormLogin(): string {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        return $this->twig->render('pages/shops/forms/__form_login.html.twig', [
            'form' => $form->createView(),
            'user' => $user,
        ]);
    }

    /**
     * @return string
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function getFormRegister(): string {
        $user = new User();
        $form = $this->createForm(UserRegistrationType::class, $user);
        return $this->twig->render('pages/shops/forms/__form_register.html.twig', [
            'form' => $form->createView(),
            'user' => $user,
        ]);
    }

    /**
     * @param $type
     * @param null $data
     * @param array $options
     * @return mixed
     */
    private function createForm($type, $data = null, array $options = array())
    {
        return $this->container->get('form.factory')->create($type, $data, $options);
    }
}