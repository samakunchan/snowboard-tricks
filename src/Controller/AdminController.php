<?php

namespace App\Controller;

use App\Repository\CommentRepository;
use App\Repository\TrickGroupRepository;
use App\Repository\TrickRepository;
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
     * @param TrickRepository $trickRepository
     * @param CommentRepository $commentRepository
     * @return Response
     */
    public function index(TrickGroupRepository $trickGroupRepository, TrickRepository $trickRepository, CommentRepository $commentRepository): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_REMEMBERED');
        return $this->render('admin/index.html.twig', [
            'tricks' => $trickRepository->findAll(),
            'trick_groups' => $trickGroupRepository->findAll(),
            'comments' => $commentRepository->findAll(),
        ]);
    }
}
