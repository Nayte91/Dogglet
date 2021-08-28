<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiSubresource;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity]
#[ApiResource(
    normalizationContext: ['groups' => ['read']],
    denormalizationContext: ['groups' => ['write']]
)]
class Dog
{
    use IdTrait;

    #[ORM\Column(nullable: true)]
    #[Groups(['read', 'write'])]
    public ?string $name;

    #[ORM\Column(nullable: true)]
    #[Groups(['read', 'write'])]
    public ?\DateTimeImmutable $birthDate;

    #[ORM\Column(nullable: true)]
    #[Groups(['read', 'write'])]
    public ?\DateTimeImmutable $deathDate;

    #[ORM\Column(nullable: true)]
    #[Groups(['read', 'write'])]
    public ?string $race;

    #[ORM\Column(nullable: true)]
    #[Groups(['read', 'write'])]
    public ?int $size;

    #[ORM\OneToMany(targetEntity: Weighing::class, mappedBy: 'dog', orphanRemoval: true)]
    #[Groups('read')]
    public ArrayCollection $weighings;

    #[ORM\OneToMany(targetEntity: BloodTest::class, mappedBy: 'dog', orphanRemoval: true)]
    #[Groups('read')]
    public ArrayCollection $bloodTests;

    #[ORM\ManyToOne(inversedBy: 'dogs')]
    #[Groups('read')]
    public User $owner;

    public function __construct()
    {
        $this->weighings = new ArrayCollection;
        $this->bloodTests = new ArrayCollection;
    }

    public function addWeighing(Weighing $weighing): self
    {
        if (!$this->weighings->contains($weighing)) {
            $this->weighings[] = $weighing;
            $weighing->dog = $this;
        }

        return $this;
    }

    public function removeWeighing(Weighing $weighing): self
    {
        if ($this->weighings->contains($weighing)) {
            $this->weighings->removeElement($weighing);
        }

        return $this;
    }

    public function addBloodTest(BloodTest $bloodTest): self
    {
        if (!$this->bloodTests->contains($bloodTest)) {
            $this->bloodTests[] = $bloodTest;
            $bloodTest->dog = $this;
        }

        return $this;
    }

    public function removeBloodTest(BloodTest $bloodTest): self
    {
        if ($this->bloodTests->contains($bloodTest)) {
            $this->weighings->removeElement($bloodTest);
        }

        return $this;
    }
}