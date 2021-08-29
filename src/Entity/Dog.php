<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity]
#[ApiResource(
    normalizationContext: ['groups' => ['dog:read']],
    denormalizationContext: ['groups' => ['dog:write']]
)]
class Dog
{
    use IdTrait;

    #[ORM\Column(nullable: true)]
    #[Groups(['dog:read', 'dog:write', 'user:read'])]
    public ?string $name;

    #[ORM\Column(nullable: true)]
    #[Groups(['dog:read', 'dog:write', 'user:read'])]
    public ?\DateTimeImmutable $birthDate = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['dog:read', 'dog:write', 'user:read'])]
    public ?\DateTimeImmutable $deathDate = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['dog:read', 'dog:write', 'user:read'])]
    public ?string $race = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['dog:read', 'dog:write', 'user:read'])]
    public ?int $size = null;

    #[ORM\ManyToOne(inversedBy: 'dogs')]
    #[Groups(['dog:read', 'dog:write'])]
    public UserAccount $owner;

    #[ORM\OneToMany(targetEntity: Weighing::class, mappedBy: 'dog', orphanRemoval: true)]
    #[Groups(['dog:read'])]
    public Collection $weighings;

    #[ORM\OneToMany(targetEntity: BloodTest::class, mappedBy: 'dog', orphanRemoval: true)]
    #[Groups(['dog:read'])]
    public Collection $bloodTests;

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