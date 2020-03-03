<?php

namespace App\Controller;

use App\Repository\TrickRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     * @param Request $request
     * @param TrickRepository $trickRepository
     * @return Response
     */
    public function index(Request $request, TrickRepository $trickRepository): Response
    {
        if ($request->isXmlHttpRequest()) {
            // 'row' vient de 'data: { row : count }' dans la requete ajax de index.html.twig
            $row = $request->query->get('row');
            return new JsonResponse([
                'view'    => $this->renderView('trick/more.html.twig', [ 'tricks' => $trickRepository->findAllLimit($row)])
            ]);
        }
        return $this->render('home/index.html.twig', [
            'tricks' => $trickRepository->findAllLimit(0), // Changer de FindAll pour ne pas tout afficher de base. Cette un findAll construit à la main (voir TrickRepository)
        ]);
    }
}
