<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class Race
{
    use IdTrait;

    #[ORM\Column]
    public array $coats = [];

    #[ORM\Column]
    public array $coatTypes = [];

    #[ORM\Column(nullable: true)]
    public ?int $pedigreeBreedNumber = null;

    #[ORM\Column]
    public int $category = 3;

    #[ORM\Column]
    public int $group = 10;

    #[ORM\Column(type: 'date', nullable: true)]
    public ?\DateTimeInterface $classificationDate = null;

    #[ORM\Column]
    public array $HereditaryFlaws = [];
}