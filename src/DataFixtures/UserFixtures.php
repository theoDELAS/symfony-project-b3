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
        
        // Admin User
        $admin = new User();
        $admin->setFirstName($faker->firstName)
             ->setLastName($faker->lastName)
             ->setBirthday($faker->dateTime)
             ->setEmail('admin@admin.com')
             ->setUsername('admin')
             ->setPassword($password)
             ->setRoles(['ROLE_ADMIN']);
        
        $this->addReference('user0', $admin);
    
        $manager->persist($admin);

        // Basic User
        for ($i = 1; $i < self::USER_COUNT; $i++) {
            $user = new User();
            $user->setFirstName($faker->firstName)
                 ->setLastName($faker->lastName)
                 ->setBirthday($faker->dateTime)
                 ->setEmail($faker->email)
                 ->setUsername($faker->userName)
                 ->setPassword($password);

            $this->addReference('user' . $i, $user);
    
            $manager->persist($user);
        }

        $manager->flush();
    }
}
