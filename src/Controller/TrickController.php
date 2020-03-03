<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Trick;
use App\Form\CommentType;
use App\Form\TrickType;
use App\Repository\CommentRepository;
use DateTime;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/trick")
 */
class TrickController extends AbstractController
{
    /**
     * @Route("/new", name="trick_new", methods={"GET","POST"})
     * @param Request $request
     * @return Response
     * @throws Exception
     */
    public function new(Request $request): Response
    {
        $trick = new Trick();
        $form = $this->createForm(TrickType::class, $trick);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $imagesCollection = $form->getData()->getImages();
            $videosCollection = $form->getData()->getVideos();
            foreach ($imagesCollection as $image)
            {
                $image->setTrick($trick);
                $image->setCreatedAt(new DateTime('now'));
                $trick->addImage($image);
            }
            foreach ($videosCollection as $video)
            {
                $video->setTrick($trick);
                $video->setCreatedAt(new DateTime('now'));
                $trick->addVideo($video);
            }
            $trick->setCreatedAt(new DateTime('now'));
            $trick->setCreatedBy($this->getUser());
            $entityManager = $this->getDoctrine()->getManager();

            $entityManager->persist($trick);
            $entityManager->flush();
            return $this->redirectToRoute('home');
        }

        return $this->render('trick/new.html.twig', [
            'trick' => $trick,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{slug}", name="trick_show", methods={"GET","POST"})
     * @param Request $request
     * @param Trick $trick
     * @param CommentRepository $commentRepository
     * @return Response
     * @throws Exception
     */
    public function show(Request $request, Trick $trick, CommentRepository $commentRepository): Response
    {
        $comment = new Comment();
        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $comment->setUser($this->getUser());
            $comment->setTrick($trick);
            $comment->setCreatedAt(new DateTime('now'));
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($comment);
            $entityManager->flush();

            return $this->redirectToRoute('trick_show', ['slug' => $trick->getSlug()]);
        }
        if ($request->isXmlHttpRequest()) {
            $row = $request->query->get('row');
            return new JsonResponse([
                'view'    => $this->renderView('comment/more.html.twig', [ 'comments' => $commentRepository->findBy(['trick' => $trick], null, 1, $row),])
            ]);
        }
        return $this->render('trick/show.html.twig', [
            'trick' => $trick,
            'comments' => $commentRepository->findBy(['trick' => $trick], null, 1, 0),
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/edit/{slug}", name="trick_edit", methods={"GET","POST"})
     * @param Request $request
     * @param Trick $trick
     * @return Response
     * @throws Exception
     */
    public function edit(Request $request, Trick $trick): Response
    {
        $form = $this->createForm(TrickType::class, $trick);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $imagesCollection = $form->getData()->getImages();
            $videosCollection = $form->getData()->getVideos();
            foreach ($imagesCollection as $image)
            {
                $image->setTrick($trick);
                $image->setUpdatedAt(new DateTime('now'));
                $trick->addImage($image);
            }
            foreach ($videosCollection as $video)
            {
                $video->setTrick($trick);
                $video->setUpdatedAt(new DateTime('now'));
                $trick->addVideo($video);
            }
            $trick->setCreatedBy($this->getUser());
            $trick->setUpdatedAt(new DateTime('now'));
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('home');
        }
        return $this->render('trick/edit.html.twig', [
            'trick' => $trick,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="trick_delete", methods={"DELETE"})
     * @param Request $request
     * @param Trick $trick
     * @return Response
     */
    public function delete(Request $request, Trick $trick): Response
    {
        if ($this->isCsrfTokenValid('delete'.$trick->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($trick);
            $entityManager->flush();
        }

        return $this->redirectToRoute('home');
    }
}
