<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Post;
use App\Entity\Comment;
use App\Entity\PostLike;
use App\DataFixtures\UserFixtures;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class PostFixtures extends Fixture implements DependentFixtureInterface
{
    const POST_COUNT = 200;

    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr_FR');
        for ($i = 0; $i < self::POST_COUNT; $i++) {
            $post = new Post();
            $post->setPicture('https://picsum.photos/60' . random_int(0, 9) . '/60' . random_int(0, 9))
                 ->setDescription(random_int(0, 1) === 0 ? $faker->sentence : null)
                 ->setLocation(random_int(0, 1) === 0 ? $faker->city : null)
                 ->setUser($this->getReference('user' . random_int(0, UserFixtures::USER_COUNT - 1)));

            $this->addReference('post' . $i, $post);

            $manager->persist($post);
        }

        $manager->flush();
    }

    public function getDependencies() {
        return [UserFixtures::class];
    }
}
