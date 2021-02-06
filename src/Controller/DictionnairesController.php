<?php

namespace App\Controller;

use App\Repository\Dictionnaires\DictionnairesRepository;
use Knp\Component\Pager\PaginatorInterface;
use MercurySeries\FlashyBundle\FlashyNotifier;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class DictionnairesController extends AbstractController
{
    const ABCDS = ["A", "B", "C", "D", "E", "F", "G", "H", "I", "J", "K", "L", "M", "N", "O", "P", "Q", "R", "S", "T", "U", "V", "W", "X", "Y", "Z"];
    const NBRE_LEXIQUES = 10;

    /**
     * @var DictionnairesRepository
     */
    private $dictionnairesRepository;
    /**
     * @var FlashyNotifier
     */
    private $flashy;
    /**
     * @var PaginatorInterface
     */
    private $paginator;

    /**
     * DictionnairesController constructor.
     * @param PaginatorInterface $paginator
     * @param FlashyNotifier $flashy
     * @param DictionnairesRepository $dictionnairesRepository
     */
    public function __construct(
        PaginatorInterface $paginator,
        FlashyNotifier $flashy,
        DictionnairesRepository $dictionnairesRepository
    )
    {
        $this->flashy = $flashy;
        $this->dictionnairesRepository = $dictionnairesRepository;
        $this->paginator = $paginator;
    }

    /**
     * @Route("/dictionnaire-des-donnees", name="dictionnaires.index", schemes={"https"}, methods={"GET", "POST"})
     * @param Request $request
     * @param DictionnairesRepository $dictionnairesRepository
     * @return Response
     */
    public function index(Request $request, DictionnairesRepository $dictionnairesRepository)
    {
        if ($request->isXmlHttpRequest() && $request->getMethod() === 'POST'){
            $search = $request->request->get('search');
            $lexiques = $this->getLexiques($request, $dictionnairesRepository->findLexiquesSearchs($search));
            if ($lexiques){
                return new JsonResponse([
                    'status' => 'success',
                    'resultat' => $this->renderView('web/dictionnaires/partials/row.html.twig', [
                        'lexiques' => $lexiques,
                    ]),
                    'abcds' => $this->renderView('web/dictionnaires/partials/__abcds.html.twig', [
                        'abcds' => self::ABCDS,
                        'searched' => $search,
                    ]),
                    'pagination' => $this->renderView('web/dictionnaires/partials/__pagination.html.twig', [
                        'lexiques' => $lexiques,
                        'nbreLexique' => self::NBRE_LEXIQUES,
                    ]),
                ]);
            }else{
                return new JsonResponse([
                    'status' => 'error',
                ]);
            }
        }else{
            $lexiques = $this->getLexiques($request, $dictionnairesRepository->findAll());
        }

        return $this->render('web/dictionnaires/index.html.twig', [
            'title' => 'Dictionnaire de la paie',
            'lexiques' => $lexiques,
            'abcds' => self::ABCDS,
            'searched' => self::ABCDS[0],
            'nbreLexique' => self::NBRE_LEXIQUES,
            'current_page' => 'dictionnaires',
            'current_global' => 'dictionnaires'
        ]);
    }

    private function getLexiques($request, $lexiques){
        return $this->paginator->paginate(
            $lexiques, /* query NOT result */
            $request->query->getInt('page', 1)/*page number*/,
            $request->query->getInt('limit', self::NBRE_LEXIQUES)/*limit per page*/
        );
    }
}