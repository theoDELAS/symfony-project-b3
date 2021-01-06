<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\User;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends Fixture
{
    const USER_COUNT = 40;

    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function load(ObjectManager $manager)
    {
        $password = $this->passwordEncoder->encodePassword(new User(), 'password');

        $faker = Factory::create('fr_FR');
        for ($i = 0; $i < self::USER_COUNT; $i++) {
            $user = new User();
            $user->setEmail('toto@mail.com')
                 ->setFirstName($faker->firstName)
                 ->setLastName($faker->lastName)
                 ->setBirthday($faker->dateTime)
                 ->setEmail($faker->email)
                 ->setUsername($faker->userName)
                 ->setPassword($password);
    
            $manager->persist($user);
        }

        $manager->flush();
    }
}
