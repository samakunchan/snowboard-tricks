<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Repository\CommentRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/")
 */
class CommentController extends AbstractController
{
    /**
     * @Route("/admin/comment", name="comment_index", methods={"GET"})
     * @param CommentRepository $commentRepository
     * @return Response
     */
    public function index(CommentRepository $commentRepository): Response
    {
        return $this->render('comment/index.html.twig', [
            'comments' => $commentRepository->findAll(),
        ]);
    }
    /**
     * @Route("/admin/{id}", name="comment_show", methods={"GET"})
     */
    public function show(Comment $comment): Response
    {
        return $this->render('comment/show.html.twig', [
            'comment' => $comment,
        ]);
    }
    /**
     * @Route("/comment/{id}", name="comment_delete", methods={"DELETE"})
     * @param Request $request
     * @param Comment $comment
     * @return Response
     */
    public function delete(Request $request, Comment $comment): Response
    {
        if ($this->isCsrfTokenValid('delete'.$comment->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($comment);
            $entityManager->flush();
        }

        return $this->redirectToRoute('trick_show', ['slug' => $comment->getTrick()->getSlug()]);
    }

    /**
     * @Route("/comment/{id}", name="comment_signal", methods={"POST"})
     * @param Request $request
     * @param Comment $comment
     * @return Response
     */
    public function signalement(Request $request, Comment $comment): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        if ($this->isCsrfTokenValid('signaled'.$comment->getId(), $request->request->get('_token'))) {
            $comment->setNotGoodComment(true);
            $this->getDoctrine()->getManager()->flush();
            $entityManager->flush();
            return $this->redirectToRoute('trick_show', ['slug' => $comment->getTrick()->getSlug()]);
        } else if ($this->isCsrfTokenValid('regular'.$comment->getId(), $request->request->get('_token'))) {
            $comment->setNotGoodComment(false);
            $this->getDoctrine()->getManager()->flush();
            $entityManager->flush();
            return $this->redirectToRoute('comment_index');
        }
    }
}
