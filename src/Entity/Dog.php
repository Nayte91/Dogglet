<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ApiResource]
class Dog
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    public ?int $id;

    #[ORM\Column(nullable: true)]
    public ?string $name;

    #[ORM\Column(nullable: true)]
    public ?\DateTimeImmutable $birthDate;

    #[ORM\Column(nullable: true)]
    public ?\DateTimeImmutable $deathDate;

    #[ORM\Column(nullable: true)]
    public ?string $race;

    #[ORM\Column(nullable: true)]
    public ?int $size;

    #[ORM\Column]
    public array $weighings = [];

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'dogs')]
    public User $owner;
}