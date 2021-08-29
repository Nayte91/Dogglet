<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity]
#[ApiResource(
    normalizationContext: ['groups' => ['treatment:read']],
    denormalizationContext: ['groups' => ['treatment:write']],
    shortName: 'Traitement'
)]
class Treatment
{
    use IdTrait;

    #[ORM\Column(nullable: true)]
    #[Groups(['treatment:read', 'treatment:write', 'dog:read'])]
    public ?\DateTimeImmutable $date;

    #[ORM\OneToOne]
    #[Groups(['treatment:read', 'treatment:write', 'dog:read'])]
    #[ApiProperty('Type de traitement')]
    #[Assert\NotBlank]
    public TreatmentType $type;

    #[ORM\Column(nullable: true)]
    #[Groups(['treatment:read', 'treatment:write', 'dog:read'])]
    public ?string $details = null;

    #[ORM\Column]
    #[Groups(['treatment:read', 'treatment:write', 'dog:read'])]
    public int $price = 0;

    #[ORM\Column(nullable: true)]
    #[Groups(['treatment:read', 'treatment:write', 'dog:read'])]
    public ?string $comments = null;

    #[ORM\Column]
    #[Groups(['treatment:read', 'treatment:write', 'dog:read'])]
    public string $status = 'fait';

    #[ORM\ManyToOne(inversedBy: 'treatment')]
    #[Groups(['treatment:read', 'treatment:write'])]
    private ?Dog $dog = null;

    public function setDog(?Dog $dog, bool $updateRelation = true): void
    {
        $this->dog = $dog;
        if ($updateRelation && null !== $dog) {
            $dog->addTreatment($this, false);
        }
    }

    public function getDog(): ?Dog
    {
        return $this->dog;
    }
}