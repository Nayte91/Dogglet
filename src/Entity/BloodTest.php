<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity]
#[ApiResource(
    normalizationContext: ['groups' => ['bloodTest:read']],
    denormalizationContext: ['groups' => ['bloodTest:write']],
)]
class BloodTest
{
    use IdTrait;

    #[ORM\Column(nullable: true)]
    #[Groups(['bloodTest:read', 'bloodTest:write', 'dog:read'])]
    public ?\DateTimeImmutable $date;

    #[ORM\ManyToOne(inversedBy: 'bloodTest')]
    #[Groups(['bloodTest:read', 'bloodTest:write'])]
    private ?Dog $dog = null;

    //Need other values

    public function setDog(?Dog $dog, bool $updateRelation = true): void
    {
        $this->dog = $dog;
        if ($updateRelation && null !== $dog) {
            $dog->addBloodTest($this, false);
        }
    }

    public function getDog(): ?Dog
    {
        return $this->dog;
    }
}