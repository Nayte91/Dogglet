<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class BloodTest
{
    use IdTrait;

    #[ORM\Column(nullable: true)]
    public ?\DateTimeImmutable $date;

    #[ORM\ManyToOne(inversedBy: 'weighings')]
    public Dog $dog;
}