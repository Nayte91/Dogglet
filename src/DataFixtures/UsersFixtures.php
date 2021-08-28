<?php

namespace App\DataFixtures;

use App\Entity\UserAccount;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UsersFixtures extends Fixture
{
    public function __construct(
        private UserPasswordHasherInterface $passwordHasher
    ) { }

    public function load(ObjectManager $manager)
    {
        foreach ($this->getUsersData() as $userData) {
            $user = new UserAccount;

            $user->email = $userData['email'];
            $user->setPassword($this->passwordHasher->hashPassword($user, $userData['password']));
            $user->firstName = $userData['firstName'];
            $user->lastName = $userData['lastName'];
            $user->veterinarianName = $userData['veterinarianName'] ?? null ;

            $manager->persist($user);
        }

        $manager->flush();
    }

    private function getUsersData(): array
    {
        return [
            [
                'email' => 'nayte91@gmail.com',
                'password' => 'toto',
                'firstName' => 'Julien',
                'lastName' => 'Robic',
                'veterinarianName' => 'Nicolas Simmenauer'
            ],
            [
                'email' => 'shixi@gmail.com',
                'password' => 'tata',
                'firstName' => 'Carole',
                'lastName' => 'T.',
            ]
        ];
    }
}
