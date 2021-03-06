<?php

namespace App\Controller;

use App\Entity\Post;
use App\Form\PostType;
use App\Entity\Comment;
use App\Entity\PostLike;
use App\Form\CommentType;
use App\Repository\CommentRepository;
use Knp\Component\Pager\PaginatorInterface;
use Lcobucci\JWT\Builder;
use Lcobucci\JWT\Signer\Key;
use App\Service\FileUploader;
use App\Repository\PostRepository;
use Lcobucci\JWT\Signer\Hmac\Sha256;
use App\Repository\PostLikeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Cookie;
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
    public function index(PostRepository $postRepository, PaginatorInterface $paginator, Request $request): Response
    {
        $posts = $paginator->paginate(
            $postRepository->findBy([], ['createdAt' => 'DESC']),
            $request->query->getInt('page', 1),
            20
        );

        $username = $this->getUser()->getName();
        $token = (new Builder())
            ->withClaim('mercure', ['subscribe' => [sprintf("/%s", $username)]])
            ->getToken(
                new Sha256(),
                new Key($this->getParameter('mercure_secret_key'))
            );

        $response = $this->render('post/index.html.twig', [
            'posts' => $posts,
        ]);

        $response->headers->setCookie(
            new Cookie(
                'mercureAuthorization',
                $token,
                (new \DateTime())
                ->add(new \DateInterval('PT2H')),
                '/.well-known/mercure',
                null,
                false,
                true,
                false,
                'strict'
            )
        );
        
        return $response;
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

    /**
     * Show a specific post
     *
     * @Route("/post/{id}", name="post_show")
     * 
     * @param Post $post
     * @return Response
     */
    public function show(Post $post): Response {
        return $this->render('post/show.html.twig', [
            'post' => $post
        ]);
    }

    /**
     * Like or unlike a post user
     *
     * @Route("/post/{id}/like", name="post_like")
     * @IsGranted("ROLE_USER")
     * 
     * @param Post $post
     * @param PostLikeRepository $postLikeRepository
     * @param EntityManagerInterface $manager
     * @return Response
     */
    public function like(Post $post, PostLikeRepository $postLikeRepository, EntityManagerInterface $manager): Response {
        $user = $this->getUser();

        if (!$user) {
            return $this->json(['code' => 403, 'message' => 'Unauthorized'], 403);
        }

        if ($post->isLikedByUser($user)) {
            $like = $postLikeRepository->findOneBy(['post' => $post, 'user' => $user]);

            $manager->remove($like);
            $manager->flush();

            return $this->json([
                'code' => 200,
                'message' => 'Successfully unliked',
                'likes' => $postLikeRepository->count(['post' => $post])
            ], 200);
        }

        $like = new PostLike();
        $like->setPost($post)
             ->setUser($user);

        $manager->persist($like);
        $manager->flush();

        return $this->json([
            'code' => 200,
            'message' => 'Successfully liked',
            'likes' => $postLikeRepository->count(['post' => $post])
        ], 200);
    }

    /**
     * Add a comment to a post
     * 
     * @Route("/post/{id}/comment", name="post_comment")
     *
     * @param Post $post
     * @param PostRepository $postRepository
     * @param EntityManagerInterface $manager
     * @return Response
     */
    public function comment(Post $post, CommentRepository $commentRepository, EntityManagerInterface $manager, Request $request) {
        $user = $this->getUser();

        if (!$user) {
            return $this->json(['code' => 403, 'message' => 'Unauthorized'], 403);
        }

        if ($request->isXmlHttpRequest()) {
            if ($content = $request->getContent()) {
                $data = json_decode($content, true);

                if (!$this->isCsrfTokenValid('form-comment', $data['token']) || !$data['comment']) {
                    return $this->json([
                        'code' => 400,
                        'message' => 'Bad requests',
                        'comments' => $commentRepository->count(['post' => $post])
                    ], 400);
                }

                $comment = new Comment();
                $comment->setUser($user)
                        ->setComment($data['comment'])
                        ->setPost($post);

                $manager->persist($comment);
                $manager->flush();

                return $this->json([
                    'code' => 200,
                    'message' => 'Successfully commented',
                    'comments' => $commentRepository->count(['post' => $post])
                ], 200);
            }
        }

        return $this->json(['code' => 400, 'message' => 'Bad request'], 400);
    }
}
