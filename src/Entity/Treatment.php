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
        'groups' => ['treatment:read'],
        'datetime_format' => 'j/m/Y'
    ],
    denormalizationContext: ['groups' => ['treatment:write']]
)]
class Treatment
{
    use IdTrait;

    #[ORM\Column(type: 'date', nullable: true)]
    #[Groups(['treatment:read', 'treatment:write', 'dog:read'])]
    #[Assert\Type(\DateTimeInterface::class)]
    public ?\DateTimeInterface $date;

    #[ORM\Column]
    #[Groups(['treatment:read', 'treatment:write', 'dog:read'])]
    #[Assert\NotBlank]
    public string $type = 'Autre';

    #[ORM\Column(nullable: true)]
    #[Groups(['treatment:read', 'treatment:write', 'dog:read'])]
    public ?string $details = null;

    #[ORM\Column]
    #[Groups(['treatment:read', 'treatment:write', 'dog:read'])]
    #[Assert\PositiveOrZero]
    public float $price = 0;

    #[ORM\Column(nullable: true)]
    #[Groups(['treatment:read', 'treatment:write', 'dog:read'])]
    public ?string $comments = null;

    #[ORM\Column]
    #[Groups(['treatment:read', 'treatment:write', 'dog:read'])]
    #[Assert\NotBlank]
    public string $status = 'Done';

    #[ORM\ManyToOne(inversedBy: 'treatment')]
    #[Groups(['treatment:read', 'treatment:write'])]
    private ?Dog $dog = null;

    public static array $status_list = ['To do', 'Planned', 'In Progress', 'Done', 'Cancelled'];

    public static array $status_type = ['Autre', 'Vaccin', 'Antiparasitaire', 'Maladie', 'Opération', 'Accident', 'Toilettage'];

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