<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Message;
use App\Entity\Participant;
use App\Entity\Conversation;
use App\DataFixtures\UserFixtures;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class ConversationFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr_FR');

        $conversation = new Conversation();
        $message = new Message();
        $message2 = new Message();
        $participant = new Participant();
        $participant2 = new Participant();

        $message->setContent($faker->sentence);
        $message->setUser($this->getReference('user0'));
        $message->setConversation($conversation);

        $message2->setContent($faker->sentence);
        $message2->setUser($this->getReference('user1'));
        $message2->setConversation($conversation);

        $participant->setUser($this->getReference('user0'));
        $participant->setConversation($conversation);
        
        $participant2->setUser($this->getReference('user1'));
        $participant2->setConversation($conversation);

        $conversation->setLastMessage($message2);
                
        $manager->persist($conversation);
        $manager->persist($message);
        $manager->persist($message2);
        $manager->persist($participant);
        $manager->persist($participant2);
   
        $manager->flush();
    }

    public function getDependencies() {
        return [
            UserFixtures::class
        ];
    }
}
