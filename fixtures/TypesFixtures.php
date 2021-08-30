<?php

namespace App\Fixtures;

use App\Entity\TreatmentType;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class TypesFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $this->loadTreatmentTypes($manager);

        $manager->flush();
    }

    private function loadTreatmentTypes(ObjectManager $manager): void
    {
        foreach ($this->getTreatmentTypesData() as $treatmentTypeData) {
            $treatmentType = new TreatmentType;

            $treatmentType->name = $treatmentTypeData;

            $manager->persist($treatmentType);
        }
    }

    private function getTreatmentTypesData(): array
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
