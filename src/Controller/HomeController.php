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
        $trickToShow = 4;
        if ($request->isXmlHttpRequest()) {
            $row = $request->query->get('row');
            return new JsonResponse([
                'view'    => $this->renderView('trick/more.html.twig', [ 'tricks' => $trickRepository->findAllLimit($row, $trickToShow)])
            ]);
        }
        return $this->render('home/index.html.twig', [
            'tricks' => $trickRepository->findAllLimit(0, $trickToShow),
            'trickToShow' => $trickToShow
        ]);
    }
}
