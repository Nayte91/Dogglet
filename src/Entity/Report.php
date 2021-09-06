<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity]
#[ApiResource(
    normalizationContext: [
        'groups' => ['report:read'],
        'datetime_format' => 'j/m/Y'
    ],
    denormalizationContext: ['groups' => ['report:write']]
)]
class Report
{
    use IdTrait;

    #[ORM\Column(type: 'date', nullable: true)]
    #[Groups(['report:read', 'report:write', 'dog:read'])]
    #[Assert\Type(\DateTimeInterface::class)]
    #[ApiProperty(description: 'Date du signalement', example: '24/12/2008')]
    public ?\DateTimeInterface $date;

    #[ORM\Column]
    #[Groups(['report:read', 'report:write', 'dog:read'])]
    #[Assert\NotBlank]
    public string $details;

    #[ORM\ManyToOne(inversedBy: 'report')]
    #[Groups(['report:read', 'report:write'])]
    #[ApiProperty(description: 'Chien', example: '/api/dogs/1')]
    private ?Dog $dog;

    public function setDog(?Dog $dog, bool $updateRelation = true): void
    {
        $this->dog = $dog;
        if ($updateRelation && null !== $dog) {
            $dog->addReport($this, false);
        }
    }

    public function getDog(): ?Dog
    {
        return $this->dog;
    }
}