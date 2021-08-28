<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
/* Une pesée en français */
class Weighing
{
    use IdTrait;

    #[ORM\Column(nullable: true)]
    public ?\DateTimeImmutable $date;

    #[ORM\Column]
    public int $weight;

    #[ORM\ManyToOne(inversedBy: 'bloodTests')]
    public Dog $dog;
}