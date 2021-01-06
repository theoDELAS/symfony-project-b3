<?php

namespace App\DataFixtures;

use App\Entity\PostLike;
use App\DataFixtures\PostFixtures;
use App\DataFixtures\UserFixtures;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class PostLikeFixtures extends Fixture implements DependentFixtureInterface
{
    const MAX_LIKE_PER_POST = 20;

    public function load(ObjectManager $manager)
    {
        for ($i = 0; $i < PostFixtures::POST_COUNT; $i++) {
            $userIds = range(0, UserFixtures::USER_COUNT - 1);
            shuffle($userIds);
            
            for ($j = 0; $j < random_int(0, self::MAX_LIKE_PER_POST); $j++) {
                $like = new PostLike();
                $like->setUser($this->getReference('user' . array_pop($userIds)))
                     ->setPost($this->getReference('post' . $i));
                
                $manager->persist($like);
            }
        }

        $manager->flush();
    }

    public function getDependencies() {
        return [
            UserFixtures::class,
            PostFixtures::class
        ];
    }
}
