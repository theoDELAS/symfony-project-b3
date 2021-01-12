<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\AvatarFormType;
use App\Form\ProfileFormType;
use App\Service\FileUploader;
use App\Form\EditPasswordFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

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

      /**
     * @IsGranted("IS_AUTHENTICATED_FULLY")
     * @Route("/profile/edit", name="profile_edit")
     */
    public function editProfile(Request $request, EntityManagerInterface $manager)
    {
        $user = $this->getUser();
        $form = $this->createForm(ProfileFormType::class, $user);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $user->setUpdatedAt(new \DateTime());
            $manager->persist($user);
            $manager->flush();

            $this->addFlash('green', 'Informations personnelles modifiées');
            return $this->redirectToRoute('profile');
        }

        return $this->render('user/edit-profile.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @IsGranted("IS_AUTHENTICATED_FULLY")
     * @Route("/profile/edit-avatar", name="avatar_edit")
     */
    public function editAvatar(Request $request, EntityManagerInterface $manager, FileUploader $fileUploader)
    {
        $user = $this->getUser();
        $form = $this->createForm(AvatarFormType::class, $user);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $avatar = $form->get('picture')->getData();
            if ($avatar) {
                $filename = $fileUploader->upload($avatar);
                $user->setAvatar($filename);
            }
            $manager->persist($user);
            $manager->flush();

            $this->addFlash('green', 'Avatar modifié');
            return $this->redirectToRoute('profile');
        }

        return $this->render('user/edit-avatar.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @IsGranted("IS_AUTHENTICATED_FULLY")
     * @Route("/profile/edit-password", name="password_edit")
     */
    public function editPassword(Request $request, EntityManagerInterface $manager, UserPasswordEncoderInterface $encoder)
    {
        $form = $this->createForm(EditPasswordFormType::class);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $user = $this->getUser();
            $user->setPassword($encoder->encodePassword($user, $form->get('plainPassword')->getData()));
            $manager->persist($user);
            $manager->flush();

            $this->addFlash('green', 'Mot de passe modifié');
            return $this->redirectToRoute('profile');
        }

        return $this->render('user/edit_password.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
