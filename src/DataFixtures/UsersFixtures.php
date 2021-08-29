<?php

namespace App\DataFixtures;

use App\Entity\Dog;
use App\Entity\UserAccount;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use phpDocumentor\Reflection\Types\This;
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
            $user->firstName = $userData['firstName'] ?? null;
            $user->lastName = $userData['lastName'] ?? null;
            $user->veterinarianName = $userData['veterinarianName'] ?? null;

            $manager->persist($user);

            if ($user->firstName === 'Julien') {
                $juliensDogData = $this->getJuliensDogsData();
                $dog = new Dog;

                $dog->name = $juliensDogData['name'];
                $dog->race = $juliensDogData['race'];
                $dog->birthDate = new \DateTimeImmutable($juliensDogData['birthDate']);

                $manager->persist($dog);
                $user->addDog($dog);
            } else {
                $caroleDogData = $this->getCarolesDogsData();
                $dog = new Dog;
                $dog->name = $caroleDogData['name'];
                $dog->race = $caroleDogData['race'];

                $manager->persist($dog);
                $user->addDog($dog);
            }
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

    public function getJuliensDogsData(): array
    {
        return [
            'name' => 'ruby',
            'race' => 'Spitz nain',
            'birthDate' => '01/09/2019'
        ];
    }

    public function getCarolesDogsData(): array
    {
        return [
            'name' => 'Suki',
            'race' => 'Berger Australien'
        ];
    }
}
