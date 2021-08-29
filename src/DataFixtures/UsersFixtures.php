<?php

namespace App\DataFixtures;

use App\Entity\Dog;
use App\Entity\TreatmentType;
use App\Entity\UserAccount;
use App\Entity\Weighing;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UsersFixtures extends Fixture
{
    public function __construct(
        private UserPasswordHasherInterface $passwordHasher
    ) {
    }

    public function load(ObjectManager $manager)
    {
        $this->loadTreatmentTypes($manager);

        $this->loadCarole($manager);
        $this->loadJulien($manager);

        $manager->flush();
    }

    private function loadTreatmentTypes(ObjectManager $manager): void
    {
        foreach ($this->getTreatmentTypes() as $treatmentTypeData) {
            $treatmentType = new TreatmentType;

            $treatmentType->name = $treatmentTypeData;

            $manager->persist($treatmentType);
        }

        $manager->flush();
    }

    private function loadCarole(ObjectManager $manager): void
    {
        $caroleData = $this->getCarolesData();
        $carole = new UserAccount;

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

        foreach ($this->getSukiWeighings() as $date => $value) {
            $weigh = new Weighing;

            $weigh->setDog($dog);
            $weigh->date = \DateTimeImmutable::createFromFormat('j/m/Y', $date);
            $weigh->weight = $value;

            $manager->persist($weigh);
        }
    }

    private function loadJulien(ObjectManager $manager): void
    {
        $juliensData = $this->getJuliensData();

        $julien = new UserAccount;

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

    private function getSukiWeighings(): array
    {
        return [
            '28/01/2021' => 420,
            '29/01/2021' => 410,
            '30/01/2021' => 460,
            '31/01/2021' => 520,
            '01/02/2021' => 600,
            '22/02/2021' => 2000,
            '29/03/2021' => 5200,
            '31/03/2021' => 5200,
            '26/04/2021' => 7800,
            '18/05/2021' => 9800,
            '28/05/2021' => 10700,
            '10/06/2021' => 11200,
            '27/06/2021' => 12600,
            '19/07/2021' => 13700,
            '29/07/2021' => 13800,
            '11/08/2021' => 14500,
            '26/08/2021' => 15200,
        ];
    }

    private function getTreatmentTypes(): array
    {
        return [
            'Vaccin',
            'Vermifuge',
            'Antipuces / tiques',
            'Maladie',
            'Opération',
            'Accident',
            'Soin',
            'Autre'
        ];
    }
}
