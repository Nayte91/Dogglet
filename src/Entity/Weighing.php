<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity]
#[ApiResource(
    normalizationContext: ['groups' => ['weighing:read']],
    denormalizationContext: ['groups' => ['weighing:write']],
    shortName: 'Pesee'
)]
class Weighing
{
    use IdTrait;

    #[ORM\Column(nullable: true)]
    #[Groups(['weighing:read', 'weighing:write', 'dog:read'])]
    public ?\DateTimeImmutable $date;

    #[ORM\Column]
    #[Groups(['weighing:read', 'weighing:write', 'dog:read'])]
    public int $weight;

    #[ORM\ManyToOne(inversedBy: 'weighings')]
    #[Groups(['weighing:read', 'weighing:write'])]
    public Dog $dog;
}