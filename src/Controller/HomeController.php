<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index()
    {
        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }
    /**
     * @Route("/single", name="single")
     */
    public function single()
    {
        return $this->render('home/temporaire/single.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }
    /**
     * @Route("/add", name="add")
     */
    public function add()
    {
        return $this->render('home/temporaire/add.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }
    /**
     * @Route("/edit", name="edit")
     */
    public function edit()
    {
        return $this->render('home/temporaire/edit.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }
    /**
     * @Route("/login", name="login")
     */
    public function login()
    {
        return $this->render('home/temporaire/login.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }
    /**
     * @Route("/register", name="register")
     */
    public function register()
    {
        return $this->render('home/temporaire/register.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }
    /**
     * @Route("/admin", name="admin")
     */
    public function admin()
    {
        return $this->render('home/temporaire/admin.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }
    /**
     * @Route("/profil", name="profil")
     */
    public function profil()
    {
        return $this->render('home/temporaire/profil.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }
    /**
     * @Route("/group/add", name="group_add")
     */
    public function groupAdd()
    {
        return $this->render('home/temporaire/group-add.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }
    /**
     * @Route("/group/edit", name="group_edit")
     */
    public function groupEdit()
    {
        return $this->render('home/temporaire/group-edit.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }
    /**
     * @Route("/users", name="users")
     */
    public function users()
    {
        return $this->render('home/temporaire/users.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }
}
