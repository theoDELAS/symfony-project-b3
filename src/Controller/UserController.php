<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class UserController extends AbstractController
{
    /**
     * @Route("/profile", name="profile")
     */
    public function details(User $user = null): Response
    {
        $user = $user ?? $this->getUser();

        if( !$user) {
            return $this->redirectToRoute('login');
        }

        return $this->render('user/details.html.twig', [
            'user' => $user
        ]);
    }
}
