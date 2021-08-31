<?php

namespace App\Fixtures;

use App\Entity\Dog;
use App\Entity\Weighing;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class WeighingFixtures extends Fixture implements DependentFixtureInterface
{

    public function load(ObjectManager $manager)
    {
        $this->loadWeighings($manager);

        $manager->flush();
    }

    private function loadWeighings(ObjectManager $manager): void
    {
        $suki = $manager->getRepository(Dog::class)->findOneBy(['name' => 'Suki']);

        foreach ($this->getSukiWeighingsData() as $date => $value) {
            $weight = new Weighing;

            $weight->setDog($suki);
            $weight->date = \DateTimeImmutable::createFromFormat('j/m/Y', $date);
            $weight->weight = $value;

            $manager->persist($weight);
        }
    }

    private function getSukiWeighingsData(): array
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

    public function getDependencies()
    {
        return [
            MasterFixtures::class
        ];
    }
}
