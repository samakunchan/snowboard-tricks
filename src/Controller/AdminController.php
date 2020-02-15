<?php

namespace App\Controller;

use App\Repository\TrickGroupRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin")
 */
class AdminController extends AbstractController
{
    /**
     * @Route("/", name="admin")
     * @param TrickGroupRepository $trickGroupRepository
     * @return Response
     */
    public function index(TrickGroupRepository $trickGroupRepository): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_REMEMBERED');
        // TODO Il faudra un findAll Trick
        return $this->render('admin/index.html.twig', [
            'trick_groups' => $trickGroupRepository->findAll(),
        ]);
    }
}
