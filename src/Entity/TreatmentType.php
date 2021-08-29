<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class TreatmentType
{
    use IdTrait;

    #[ORM\Column]
    public string $name;
}