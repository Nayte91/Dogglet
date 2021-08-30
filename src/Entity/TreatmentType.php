<?php

namespace App\Entity;

use App\Repository\TreatmentTypeRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(TreatmentTypeRepository::class)]
class TreatmentType
{
    use IdTrait;

    #[ORM\Column]
    public string $name;
}