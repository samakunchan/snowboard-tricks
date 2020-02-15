<?php

namespace App\Controller;

use App\Entity\TrickGroup;
use App\Form\TrickGroupType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/group")
 */
class TrickGroupController extends AbstractController
{
    /**
     * @Route("/new", name="trick_group_new", methods={"GET","POST"})
     * @param Request $request
     * @return Response
     */
    public function new(Request $request): Response
    {
        $trickGroup = new TrickGroup();
        $form = $this->createForm(TrickGroupType::class, $trickGroup);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $trickGroup->setTitle(strtolower($form->getData()->getTitle()));
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($trickGroup);
            $entityManager->flush();

            return $this->redirectToRoute('admin');
        }

        return $this->render('trick_group/new.html.twig', [
            'trick_group' => $trickGroup,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/edit", name="trick_group_edit", methods={"GET","POST"})
     * @param Request $request
     * @param TrickGroup $trickGroup
     * @return Response
     */
    public function edit(Request $request, TrickGroup $trickGroup): Response
    {
        $form = $this->createForm(TrickGroupType::class, $trickGroup);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('admin');
        }

        return $this->render('trick_group/edit.html.twig', [
            'trick_group' => $trickGroup,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="trick_group_delete", methods={"DELETE"})
     * @param Request $request
     * @param TrickGroup $trickGroup
     * @return Response
     */
    public function delete(Request $request, TrickGroup $trickGroup): Response
    {
        if ($this->isCsrfTokenValid('delete'.$trickGroup->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($trickGroup);
            $entityManager->flush();
        }

        return $this->redirectToRoute('admin');
    }
}
