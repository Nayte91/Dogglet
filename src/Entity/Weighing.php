<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity]
#[ApiResource(
    normalizationContext: [
        'groups' => ['weighing:read'],
        'datetime_format' => 'j/m/Y'
    ],
    denormalizationContext: ['groups' => ['weighing:write']],
)]
class Weighing
{
    use IdTrait;

    #[ORM\Column(nullable: true)]
    #[Groups(['weighing:read', 'weighing:write', 'dog:read'])]
    public ?\DateTimeImmutable $date;

    #[ORM\Column]
    #[Groups(['weighing:read', 'weighing:write', 'dog:read'])]
    #[Assert\Positive]
    public int $weight;

    #[ORM\ManyToOne(inversedBy: 'weighing')]
    #[Groups(['weighing:read', 'weighing:write'])]
    private ?Dog $dog;

    public function setDog(?Dog $dog, bool $updateRelation = true): void
    {
        $this->dog = $dog;
        if ($updateRelation && null !== $dog) {
            $dog->addWeighing($this, false);
        }
    }

    public function getDog(): ?Dog
    {
        return $this->dog;
    }
}