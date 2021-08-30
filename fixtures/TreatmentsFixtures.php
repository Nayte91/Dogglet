<?php

namespace App\Fixtures;

use App\Entity\Dog;
use App\Entity\Treatment;
use App\Entity\TreatmentType;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class TreatmentsFixtures extends Fixture implements DependentFixtureInterface
{

    public function load(ObjectManager $manager)
    {
        $this->loadTreatments($manager);

        $manager->flush();
    }

    private function loadTreatments(ObjectManager $manager): void
    {
        $suki = $manager->getRepository(Dog::class)->findOneBy(['name' => 'Suki']);
        foreach ($this->getTreatmentsData() as $treatmentData) {
            $treatment = new Treatment;

            $treatment->setDog($suki);
            $treatment->date = \DateTimeImmutable::createFromFormat('j/m/Y', $treatmentData['date']);
            $treatment->type = $manager->getRepository(TreatmentType::class)->findOneBy(['name' => $treatmentData['type']]);
            $treatment->details = $treatmentData['details'] ?? null;
            $treatment->price = $treatmentData['price'] ?? 0;
            $treatment->status = $treatmentData['status'] ?? 'Done';
            $treatment->comments = $treatmentData['comments'] ?? null;

            $manager->persist($treatment);
        }
    }

    private function getTreatmentsData(): array
    {
        return [
            [
                'date' => '29/03/2021',
                'type' => 'Vaccin',
                'details' => 'Vaccin CHLPPi'
            ],
            [
                'date' => '31/03/2021',
                'type' => 'Antipuces / tiques',
                'details' => 'Pipette Advantix 4 - 10kg'
            ],
            [
                'date' => '26/04/2021',
                'type' => 'Vaccin',
                'details' => 'Vaccin CHPPI + L4',
                'price' => 71
            ],
            [
                'date' => '26/04/2021',
                'type' => 'Autre',
                'details' => 'Antagène MDR1',
                'price' => 75
            ],
            [
                'date' => '28/04/2021',
                'type' => 'Vermifuge',
                'details' => 'Drontal 10kg'
            ],
            [
                'date' => '01/05/2021',
                'type' => 'Antipuces / tiques',
                'details' => 'Pipette Vectra 4 - 10kg'
            ],
            [
                'date' => '18/05/2021',
                'type' => 'Vaccin',
                'details' => 'L4 + Rage',
                'price' => 80
            ],
            [
                'date' => '29/05/2021',
                'type' => 'Antipuces / tiques',
                'details' => 'Drontal 2 x 10kg'
            ],
            [
                'date' => '02/06/2021',
                'type' => 'Antipuces / tiques',
                'details' => 'Vectra 10 - 25kg'
            ],
            [
                'date' => '10/06/2021',
                'type' => 'Maladie',
                'details' => 'Véto - Diarrhées et vomissement suite à ingestion spianata picante',
                'price' => 108.30,
                'comments' => 'Injection antivomitif et antidiarrhéique + Canidiarix / Posphaluvet'
            ],
            [
                'date' => '29/06/2021',
                'type' => 'Antipuces / tiques',
                'details' => 'Vectra 10 - 25kg'
            ],
            [
                'date' => '02/07/2021',
                'type' => 'Vermifuge',
                'details' => 'Vectra 10 - 25kg'
            ],
            [
                'date' => '19/07/2021',
                'type' => 'Opération',
                'details' => 'Stérilisation',
                'price' => 550
            ],
            [
                'date' => '29/07/2021',
                'type' => 'Opération',
                'details' => 'Retrait des fils'
            ],
            [
                'date' => '01/08/2021',
                'type' => 'Vermifuge',
                'details' => 'Vectra 10 - 25kg'
            ],
            [
                'date' => '04/08/2021',
                'type' => 'Antipuces / tiques',
                'details' => 'Advantix 10 - 25kg'
            ],
            [
                'date' => '28/08/2021',
                'type' => 'Antipuces / tiques',
                'details' => 'Advantix 10 - 25kg'
            ],
            [
                'date' => '28/09/2021',
                'type' => 'Antipuces / tiques',
                'details' => 'Advantix 10 - 25kg',
                'status' => 'Planned'
            ],
            [
                'date' => '08/10/2021',
                'type' => 'Vermifuge',
                'status' => 'Planned'
            ],
        ];
    }

    public function getDependencies()
    {
        return [
            TypesFixtures::class,
            UsersFixtures::class
        ];
    }
}
