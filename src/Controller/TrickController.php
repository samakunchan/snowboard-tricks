<?php

namespace App\Controller;

use App\Entity\Trick;
use App\Form\TrickType;
use App\Service\FileService;
use DateTime;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
     * @Route("/{slug}", name="trick_show", methods={"GET"})
     * @param Trick $trick
     * @return Response
     */
    public function show(Trick $trick): Response
    {
        return $this->render('trick/show.html.twig', [
            'trick' => $trick,
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
     * @param FileService $fileService
     * @return Response
     */
    public function delete(Request $request, Trick $trick, FileService $fileService): Response
    {
        if ($this->isCsrfTokenValid('delete'.$trick->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            foreach ($trick->getImages() as $image){
                $fileService->deleteImageDir($image);
            }
            $entityManager->remove($trick);
            $entityManager->flush();
        }

        return $this->redirectToRoute('home');
    }
}
