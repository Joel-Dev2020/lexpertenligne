<?php

namespace App\Extensions\Twig;

use App\Repository\AbonnesRepository;
use App\Repository\Blogs\BlogsRepository;
use App\Repository\Blogs\CategoriesblogsRepository;
use App\Repository\ContactsRepository;
use App\Repository\Dossiers\DossiersRepository;
use App\Repository\LogsRepository;
use App\Repository\NotificationsRepository;
use Twig\Environment;
use Twig\TwigFunction;
use Psr\Container\ContainerInterface;
use Twig\Extension\AbstractExtension;
use Symfony\Contracts\Cache\CacheInterface;

class PartialsExtension extends AbstractExtension
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
     * @var LogsRepository
     */
    private $logsRepository;
    /**
     * @var NotificationsRepository
     */
    private $notificationsRepository;
    /**
     * @var AbonnesRepository
     */
    private $abonnesRepository;

    /**
     * @var CategoriesblogsRepository
     */
    private $categoriesblogsRepository;
    /**
     * @var BlogsRepository
     */
    private $blogsRepository;
    /**
     * @var DossiersRepository
     */
    private $dossiersRepository;
    /**
     * @var ContactsRepository
     */
    private $contactsRepository;

    /**
     * PartialsExtension constructor.
     * @param Environment $twig
     * @param CacheInterface $cache
     * @param ContainerInterface $container
     * @param CategoriesblogsRepository $categoriesblogsRepository
     * @param BlogsRepository $blogsRepository
     * @param DossiersRepository $dossiersRepository
     * @param NotificationsRepository $notificationsRepository
     * @param AbonnesRepository $abonnesRepository
     * @param ContactsRepository $contactsRepository
     * @param LogsRepository $logsRepository
     */
    public function __construct(
        Environment $twig,
        CacheInterface $cache,
        ContainerInterface $container,
        CategoriesblogsRepository $categoriesblogsRepository,
        BlogsRepository $blogsRepository,
        DossiersRepository $dossiersRepository,
        NotificationsRepository $notificationsRepository,
        AbonnesRepository $abonnesRepository,
        ContactsRepository $contactsRepository,
        LogsRepository $logsRepository
    )
    {
        $this->twig = $twig;
        $this->cache = $cache;
        $this->container = $container;
        $this->logsRepository = $logsRepository;
        $this->notificationsRepository = $notificationsRepository;
        $this->abonnesRepository = $abonnesRepository;
        $this->categoriesblogsRepository = $categoriesblogsRepository;
        $this->blogsRepository = $blogsRepository;
        $this->dossiersRepository = $dossiersRepository;
        $this->contactsRepository = $contactsRepository;
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('locale_links', [$this, 'getLocaleLinks'], ['is_safe' => ['html']]),
            new TwigFunction('fil_arial', [$this, 'getFilArial'], ['is_safe' => ['html']]),
            new TwigFunction('fil_arial_admin_categories', [$this, 'getFilArialAdminCategories'], ['is_safe' => ['html']]),
            new TwigFunction('count_abonnes_no_active', [$this, 'getCountAbonnesNoActive'], ['is_safe' => ['html']]),
            new TwigFunction('count_contacts_no_active', [$this, 'getCountContactsNoActive'], ['is_safe' => ['html']]),
            new TwigFunction('get_notifications', [$this, 'getNotifications'], ['is_safe' => ['html']]),
            new TwigFunction('count_notifs', [$this, 'getCountNotifications'], ['is_safe' => ['html']]),
            new TwigFunction('get_lastestblogs', [$this, 'getLatsetBlogs'], ['is_safe' => ['html']]),
            new TwigFunction('get_lastestdossiers', [$this, 'getLatestDossiers'], ['is_safe' => ['html']]),
            new TwigFunction('get_mainslider', [$this, 'getSlider'], ['is_safe' => ['html']]),
            new TwigFunction('get_featureddossier', [$this, 'getFeaturedDossier'], ['is_safe' => ['html']]),
            new TwigFunction('get_notifications', [$this, 'getNotifications'], ['is_safe' => ['html']]),
        ];
    }

    public function getLocaleLinks(): string {
        return $this->twig->render('layouts/front/partials/__locale_link.html.twig');
    }

    public function getFilArial($title): string {
        return $this->twig->render('layouts/front/partials/filarials/__fil_arial.html.twig', ['title' => $title]);
    }

    public function getCountAbonnesNoActive(): int {
        return $this->abonnesRepository->findAbonnes();
    }

    public function getCountContactsNoActive(): int {
        return $this->abonnesRepository->findAbonnes();
    }

    public function getNotifications(): string {
        return $this->twig->render('layouts/partials/__notifications.html.twig', [
            'notifications' => $this->notificationsRepository->findNotifsUnread(5)
        ]);
    }

    public function getLatsetBlogs(): string {
        $blogs = $this->blogsRepository->findBy(['online' => true], ['id' => 'DESC'], 3);
        return $this->twig->render('web/blogs/partials/__latsetblogs.html.twig', [
            'blogs' =>  $blogs
        ]);
    }

    public function getLatestDossiers(): string {
        $dossiers = $this->dossiersRepository->findBy(['online' => true], ['id' => 'DESC'], 3);
        return $this->twig->render('web/dossiers/partials/__latsetdossiers.html.twig', [
            'dossiers' =>  $dossiers
        ]);
    }

    public function getFeaturedDossier(): string {
        $dossier = $this->dossiersRepository->findFeaturedDossier();
        return $this->twig->render('web/dossiers/partials/__featueddossiers.html.twig', [
            'dossier' =>  $dossier
        ]);
    }

    public function getSlider(): string {
        $dossiers = $this->dossiersRepository->findBy(['online' => true], ['id' => 'DESC'], 4);
        return $this->twig->render('web/partials/__slider.html.twig', [
            'dossiers' =>  $dossiers
        ]);
    }

    public function getFilArialAdminCategories(string $title, ?string $cat, ?string $libelle): string {
        return $this->twig->render('layouts/admin/partials/filarials/__filarial.html.twig', [
            'title' => $title,
            'cat' => $cat,
            'libelle' => $libelle
        ]);
    }
}