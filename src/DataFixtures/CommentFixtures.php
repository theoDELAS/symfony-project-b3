<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Comment;
use App\Entity\CommentLike;
use App\DataFixtures\PostFixtures;
use App\DataFixtures\UserFixtures;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class CommentFixtures extends Fixture implements DependentFixtureInterface
{
    const MAX_COMMENT_PER_POST = 3;
    const MAX_LIKE_PER_COMMENT = 3;

    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr_FR');
        for ($i = 0; $i < PostFixtures::POST_COUNT; $i++) {
            $userIds = range(0, UserFixtures::USER_COUNT - 1);
            shuffle($userIds);

            for ($j = 0; $j < random_int(0, self::MAX_COMMENT_PER_POST); $j++) {
                $comment = new Comment();
                $comment->setComment($faker->sentence)
                        ->setUser($this->getReference('user' . array_pop($userIds)))
                        ->setPost($this->getReference('post' . $i));

                $commentLikesUIDs = range(0, UserFixtures::USER_COUNT - 1);
                shuffle($commentLikesUIDs);

                for ($k = 0; $k < random_int(0, self::MAX_LIKE_PER_COMMENT); $k++) {
                    $like = new CommentLike();
                    $like->setUser($this->getReference('user' . array_pop($commentLikesUIDs)))
                         ->setComment($comment);
                         
                    $manager->persist($like);
                }
                
                $manager->persist($comment);
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
