<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity]
#[ApiResource(
    normalizationContext: ['groups' => ['bloodTest:read']],
    denormalizationContext: ['groups' => ['bloodTest:write']],
    shortName: 'Examens'
)]
class BloodTest
{
    use IdTrait;

    #[ORM\Column(nullable: true)]
    #[Groups(['bloodTest:read', 'bloodTest:write', 'dog:read'])]
    public ?\DateTimeImmutable $date;

    #[ORM\ManyToOne(inversedBy: 'bloodTest')]
    #[Groups(['bloodTest:read', 'bloodTest:write'])]
    public Dog $dog;

    //Need other values
}