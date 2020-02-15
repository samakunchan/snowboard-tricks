<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractController
{
    /**
     * @Route("/admin", name="admin")
     */
    public function index()
    {
        return $this->render('admin/index.html.twig', [
            'controller_name' => 'AdminController',
        ]);
    }
    /**
     * @Route("/admin", name="admin")
     */
    public function admin()
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_REMEMBERED');
        // TODO Il faudra un findAll Trick
        // TODO Il faudra un findAll TrickGroup
        return $this->render('admin/index.html.twig', [

        ]);
    }
    // /**
    //  * @Route("/group/add", name="group_add")
    //  */
    // public function groupAdd()
    // {
    //     return $this->render('home/temporaire/group-add.html.twig', [
    //         'controller_name' => 'HomeController',
    //     ]);
    // }
    // /**
    //  * @Route("/group/edit", name="group_edit")
    //  */
    // public function groupEdit()
    // {
    //     return $this->render('home/temporaire/group-edit.html.twig', [
    //         'controller_name' => 'HomeController',
    //     ]);
    // }
}
