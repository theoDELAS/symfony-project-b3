<?php

namespace App\Controller;

use Exception;
use App\Entity\Participant;
use App\Entity\Conversation;
use App\Repository\UserRepository;
use Symfony\Component\WebLink\Link;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\ConversationRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/conversations", name="conversations.")
 */

class ConversationController extends AbstractController
{

    private $userRepository;
    private $entityManager;
    private $conversationRepository;


    public function __construct(UserRepository $userRepository, EntityManagerInterface $entityManager, ConversationRepository $conversationRepository) 
    {
        $this->userRepository = $userRepository;
        $this->entityManager = $entityManager;
        $this->conversationRepository = $conversationRepository;
    }
    /**
     * @Route("/", name="newConversations", methods={"POST"})
     * @param Request $request
     * @return JsonResponse
     * @throw \Exception
     */
    public function index(Request $request)
    {
        $otherUser = $request->get('otherUser', 0);
        $otherUser = $this->userRepository->find($otherUser);

        if (is_null($otherUser)) {
            throw new Exception("L'utilisateur n'a pas été trouvé");
        }


        // cannot create conversation with myself
        if ($otherUser->getId() === $this->getUser()->getId()) 
        {
            throw new Exception("Vous ne pouvez pas créer de conversation avec vous-même");
        }

        // check if conversation already exist
        $conversation = $this->conversationRepository->findConversationByParticipants(
            $otherUser->getId(),
            $this->getUser()->getId()
        );

        
        if (count($conversation)) 
        {
            throw new \Exception("La conversation existe déjà");
        }

        $conversation = new Conversation();

        $participant = new Participant();
        $participant->setUser($this->getUser());
        $participant->setConversation($conversation);

        $otherParticipant = new Participant();
        $otherParticipant->setUser($otherUser);
        $otherParticipant->setConversation($conversation);


        $this->entityManager->getConnection()->beginTransaction();
        try {
            $this->entityManager->persist($conversation);
            $this->entityManager->persist($participant);
            $this->entityManager->persist($otherParticipant);

            $this->entityManager->flush();
            $this->entityManager->commit();
        } catch (\Exception $e) {
            $this->entityManager->rollBack();
            throw $e;
        }
    
        return $this->json([
            'id' => $conversation->getId()
        ], Response::HTTP_CREATED, [], []);
    }

    /**
     * @Route("/", name="getConversations", methods={"GET"})
     */
    public function getConvs(Request $request) 
    {
        $conversations = $this->conversationRepository->findConversationsByUser($this->getUser()->getId());

        $hubUrl = $this->getParameter('mercure.default_hub');

        $this->addLink($request, new Link('mercure', $hubUrl));

        return $this->json($conversations);
    }
}
