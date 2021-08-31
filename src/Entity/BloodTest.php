<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity]
#[ApiResource(
    normalizationContext: ['groups' => ['bloodTest:read']],
    denormalizationContext: ['groups' => ['bloodTest:write']],
)]
class BloodTest
{
    use IdTrait;

    #[ORM\Column(type: 'date', nullable: true)]
    #[Groups(['bloodTest:read', 'bloodTest:write', 'dog:read'])]
    #[Assert\Type(\DateTimeInterface::class)]
    public ?\DateTimeInterface $date = null;

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