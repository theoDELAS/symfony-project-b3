<?php

namespace App\Controller;

use App\Repository\CommentRepository;
use App\Repository\PostLikeRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class NotificationController extends AbstractController
{
    /**
     * Show notifications page
     *
     * @Route("/notifications", name="notifications")
     * @IsGranted("ROLE_USER")
     * 
     * @param PostLikeRepository $postLikeRepository
     * @param CommentRepository $commentRepository
     * @return Response
     */
    public function index(PostLikeRepository $postLikeRepository, CommentRepository $commentRepository): Response
    {
        $user = $this->getUser();
        $userPosts = $user->getPosts()->toArray();

        $likes = $postLikeRepository->findBy(['post' => $userPosts], ['likedAt' => 'DESC'], 10);
        $comments = $commentRepository->findBy(['post' => $userPosts], ['createdAt' => 'DESC'], 10);

        return $this->render('notification/index.html.twig', [
            'likes' => $likes,
            'comments' => $comments
        ]);
    }
}
