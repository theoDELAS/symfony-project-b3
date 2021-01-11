<?php

namespace App\Controller;

use App\Entity\Post;
use App\Form\PostType;
use App\Service\FileUploader;
use App\Repository\PostRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PostController extends AbstractController
{
    /**
     * @Route("/", name="post")
     * @IsGranted("ROLE_USER")
     */
    public function index(PostRepository $postRepository): Response
    {
        return $this->render('post/index.html.twig', [
            'posts' => $postRepository->findAll(),
        ]);
    }

    /**
     * @Route("/post/new", name="post_create")
     * @IsGranted("ROLE_USER")
     */
    public function create(Request $request, FileUploader $fileUploader, EntityManagerInterface $manager) {
        $post = new Post();

        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $postPicture = $form->get('picture')->getData();
            if ($postPicture) {
                $filename = $fileUploader->upload($postPicture);
                $post->setPicture($filename);
            }
            
            $post->setUser($this->getUser());

            $manager->persist($post);
            $manager->flush();

            $this->addFlash('green', 'Publication créée');

            return $this->redirectToRoute('post');
        }

        return $this->render('post/create.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
