<?php

namespace App\Security\Voter;

use App\Entity\Conversaton;
use App\Repository\ConversationRepository;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

class ConversationVoter extends Voter
{
    public function __construct(ConversationRepository $conversationRepository) 
    {
        $this->conversationRepository = $conversationRepository;
    }

    const VIEW = "view";

    protected function supports(string $attribute, $subject)
    {
        return $attribute == self::VIEW && $subject instanceof Conversation;
    }

    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $tokenInterface)
    {
        $result = $this->conversationRepository->checkIfUserIsParticipant(
            $subject->getId(),
            $token->getUser()->getId()
        );

        dd($result);

        return !!result;
    }

}