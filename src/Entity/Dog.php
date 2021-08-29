<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Serializer\Annotation\Groups;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity]
#[ApiResource(
    normalizationContext: [
        'groups' => ['dog:read'],
        'datetime_format' => 'j/m/Y'
    ],
    denormalizationContext: ['groups' => ['dog:write']],
    shortName: 'Chien'
)]
class Dog
{
    use IdTrait;

    #[ORM\Column(nullable: true)]
    #[Groups(['dog:read', 'dog:write', 'user:read'])]
    #[ApiProperty('Nom')]
    public ?string $name;

    #[ORM\Column(nullable: true)]
    #[Groups(['dog:read', 'dog:write', 'user:read'])]
    #[ApiProperty('Date de naissance')]
    #[Assert\Date]
    public ?\DateTimeImmutable $birthDate = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['dog:read', 'dog:write', 'user:read'])]
    #[ApiProperty('Date de décès')]
    #[Assert\Date]
    public ?\DateTimeImmutable $deathDate = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['dog:read', 'dog:write', 'user:read'])]
    #[Assert\NotBlank]
    public ?string $race = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['dog:read', 'dog:write', 'user:read'])]
    #[ApiProperty('Taille en cm')]
    #[Assert\Positive]
    public ?int $size = null;

    #[ORM\ManyToOne(inversedBy: 'dogs')]
    #[Groups(['dog:read', 'dog:write'])]
    #[ApiProperty('Propriétaire', iri: 'https://schema.org/Person')]
    #[Assert\NotBlank]
    public UserAccount $owner;

    #[ORM\OneToMany(targetEntity: Weighing::class, mappedBy: 'dog', orphanRemoval: true)]
    #[Groups(['dog:read'])]
    #[ApiProperty('Pesées')]
    public Collection $weighings;

    #[ORM\OneToMany(targetEntity: BloodTest::class, mappedBy: 'dog', orphanRemoval: true)]
    #[Groups(['dog:read'])]
    #[ApiProperty('Examens sanguins')]
    public Collection $bloodTests;

    #[ORM\OneToMany(targetEntity: Treatment::class, mappedBy: 'dog', orphanRemoval: true)]
    #[Groups(['dog:read'])]
    #[ApiProperty('Traitements')]
    public Collection $treatments;

    public function __construct()
    {
        $this->weighings = new ArrayCollection;
        $this->bloodTests = new ArrayCollection;
        $this->treatments = new ArrayCollection;
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
            $this->bloodTests->removeElement($bloodTest);
        }

        return $this;
    }

    public function addTreatment(Treatment $treatment): self
    {
        if (!$this->treatments->contains($treatment)) {
            $this->treatments[] = $treatment;
            $treatment->dog = $this;
        }

        return $this;
    }

    public function removeTreatment(Treatment $treatment): self
    {
        if ($this->treatments->contains($treatment)) {
            $this->treatments->removeElement($treatment);
        }

        return $this;
    }
}