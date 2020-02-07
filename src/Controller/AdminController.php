<?php

namespace App\Controller;

use App\Entity\Users;
use App\Repository\UsersRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminController extends AbstractController
{
    /**
     * Affichage du panneau d'administration
     * 
     * @Route("/admin-panel", name="admin_panel")
     */
    public function displayAdminPanel()
    {
        return $this->render('admin/admin_panel.html.twig', [
            'controller_name' => 'Administration',
        ]);
    }

    /**
     * Affichage du panneau de Gestion des utilisateurs
     * 
     * @Route("/gestion-users", name="gestion_users")
     */
    public function displayUsersManager(UsersRepository $usersRepo)
    {
        $usersAccounts = $usersRepo->findAll();

        return $this->render('admin/gestion_users.html.twig', [
            'controller_name' => 'Gestion Des Utilisateurs',
            'usersAccounts' => $usersAccounts
        ]);
    }
}
