<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiProperty;
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
    #[ApiProperty(description: 'Date de la prise de sang', example: '24/12/2008')]
    public ?\DateTimeInterface $date = null;

    #[ORM\ManyToOne(inversedBy: 'bloodTest')]
    #[Groups(['bloodTest:read', 'bloodTest:write'])]
    #[ApiProperty(description: 'Chien', example: '/api/dogs/1')]
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