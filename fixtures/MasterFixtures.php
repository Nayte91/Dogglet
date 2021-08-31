<?php

namespace App\Fixtures;

use App\Entity\Dog;
use App\Entity\Master;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class MasterFixtures extends Fixture
{
    public function __construct(
        private UserPasswordHasherInterface $passwordHasher
    ) { }

    public function load(ObjectManager $manager)
    {

        $this->loadCarole($manager);
        $this->loadJulien($manager);

        $manager->flush();
    }

    private function loadCarole(ObjectManager $manager): void
    {
        $caroleData = $this->getCarolesData();
        $carole = new Master;

        $carole->email = $caroleData['email'];
        $carole->setPassword($this->passwordHasher->hashPassword($carole, $caroleData['password']));
        $carole->firstName = $caroleData['firstName'];
        $carole->lastName = $caroleData['lastName'];

        $manager->persist($carole);

        $caroleDogData = $this->getCarolesDogsData();
        $dog = new Dog;
        $dog->name = $caroleDogData['name'];
        $dog->race = $caroleDogData['race'];
        $dog->birthDate = \DateTimeImmutable::createFromFormat('j/m/Y', $caroleDogData['birthDate']);

        $manager->persist($dog);
        $carole->addDog($dog);
    }

    private function loadJulien(ObjectManager $manager): void
    {
        $juliensData = $this->getJuliensData();

        $julien = new Master;

        $julien->email = $juliensData['email'];
        $julien->setPassword($this->passwordHasher->hashPassword($julien, $juliensData['password']));
        $julien->firstName = $juliensData['firstName'];
        $julien->lastName = $juliensData['lastName'];
        $julien->veterinarianName = $juliensData['veterinarianName'];

        $manager->persist($julien);

        $juliensDogData = $this->getJuliensDogsData();
        $dog = new Dog;

        $dog->name = $juliensDogData['name'];
        $dog->race = $juliensDogData['race'];
        $dog->size = $juliensDogData['size'];
        $dog->birthDate = \DateTimeImmutable::createFromFormat('j/m/Y', $juliensDogData['birthDate']);

        $manager->persist($dog);
        $julien->addDog($dog);
    }

    private function getJuliensData(): array
    {
        return [
            'email' => 'nayte91@gmail.com',
            'password' => 'toto',
            'firstName' => 'Julien',
            'lastName' => 'Robic',
            'veterinarianName' => 'Nicolas Simmenauer'
        ];
    }

    private function getCarolesData(): array
    {
        return [
            'email' => 'shixi@gmail.com',
            'password' => 'tata',
            'firstName' => 'Carole',
            'lastName' => 'T.',
        ];
    }

    private function getJuliensDogsData(): array
    {
        return [
            'name' => 'ruby',
            'race' => 'Spitz nain',
            'size' => 59,
            'birthDate' => '01/09/2019'
        ];
    }

    private function getCarolesDogsData(): array
    {
        return [
            'name' => 'Suki',
            'race' => 'Berger Australien',
            'birthDate' => '28/01/2021'
        ];
    }
}
