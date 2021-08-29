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
    denormalizationContext: [
        'groups' => ['dog:write'],
        'datetime_format' => 'j/m/Y'
    ],
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
    private Collection $weighings;

    #[ORM\OneToMany(targetEntity: BloodTest::class, mappedBy: 'dog', orphanRemoval: true)]
    #[Groups(['dog:read'])]
    #[ApiProperty('Examens sanguins')]
    private Collection $bloodTests;

    #[ORM\OneToMany(targetEntity: Treatment::class, mappedBy: 'dog', orphanRemoval: true)]
    #[Groups(['dog:read'])]
    #[ApiProperty('Traitements')]
    private Collection $treatments;

    public function __construct()
    {
        $this->weighings = new ArrayCollection;
        $this->bloodTests = new ArrayCollection;
        $this->treatments = new ArrayCollection;
    }

    public function addWeighing(Weighing $weighing, bool $updateRelation = true): void
    {
        if ($this->weighings->contains($weighing)) return;

        $this->weighings->add($weighing);

        $updateRelation && $weighing->setDog($this);
    }

    public function removeWeighing(Weighing $weighing, bool $updateRelation = true): void
    {
        $this->weighings->removeElement($weighing);

        $updateRelation && $weighing->setDog(null, false);
    }

    public function getWeighings(): iterable
    {
        return $this->weighings;
    }

    public function addBloodTest(BloodTest $bloodTest, bool $updateRelation = true): void
    {
        if ($this->bloodTests->contains($bloodTest)) return;

        $this->bloodTests->add($bloodTest);

        $updateRelation && $bloodTest->setDog($this);
    }

    public function removeBloodTest(BloodTest $bloodTest, bool $updateRelation = true): void
    {
        $this->bloodTests->removeElement($bloodTest);

        $updateRelation && $bloodTest->setDog(null, false);
    }

    public function getBloodTests(): iterable
    {
        return $this->bloodTests;
    }

    public function addTreatment(Treatment $treatment, bool $updateRelation = true): void
    {
        if ($this->treatments->contains($treatment)) return;

        $this->treatments->add($treatment);

        $updateRelation && $treatment->setDog($this, false);
    }

    public function removeTreatment(Treatment $treatment, bool $updateRelation = true): void
    {
        $this->treatments->removeElement($treatment);

        $updateRelation && $treatment->setDog(null, false);
    }

    public function getTreatments(): iterable
    {
        return $this->treatments;
    }
}