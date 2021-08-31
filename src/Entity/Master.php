<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiSubresource;
use App\Repository\MasterRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\SerializedName;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(MasterRepository::class)]
#[ApiResource(
    normalizationContext: [
        'groups' => ['user:read'],
        'datetime_format' => 'j/m/Y'
    ],
    denormalizationContext: ['groups' => ['user:write']],
    collectionOperations: [
        'get',
        'post' => ['validation_groups' => ['default', 'create']]
    ]
)]
class Master implements UserInterface, PasswordAuthenticatedUserInterface
{
    use IdTrait;

    #[ORM\Column(length: 180, unique: true)]
    #[Groups(['user:read', 'user:write'])]
    #[Assert\Email]
    public string $email;

    #[ORM\Column]
    private array $roles = [];

    #[ORM\Column]
    private string $password;

    #[Groups(['user:write'])]
    #[SerializedName('password')]
    #[Assert\NotBlank(groups: ['create'])]
    public ?string $plainPassword = null;

    #[ORM\Column]
    #[Groups(['user:read', 'user:write'])]
    public string $firstName;

    #[ORM\Column(nullable: true)]
    #[Groups(['user:read', 'user:write'])]
    #[Assert\NotBlank]
    public ?string $lastName = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['user:read', 'user:write'])]
    public ?string $veterinarianName = null;

    #[ORM\OneToMany(targetEntity: Dog::class, mappedBy: 'owner', cascade: ['persist', 'remove'], orphanRemoval: true)]
    #[ApiSubresource]
    #[Groups('user:read')]
    private Collection $dogs;

    public function __construct()
    {
        $this->dogs = new ArrayCollection;
    }

    public function getDogs(): iterable
    {
        return $this->dogs;
    }

    public function addDog(Dog $dog): void
    {
        if ($this->dogs->contains($dog)) return;

        $this->dogs[] = $dog;
        $dog->owner = $this;
    }

    public function removeDog(Dog $dog): void
    {
        $this->dogs->removeElement($dog);
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return $this->email;
    }

    /**
     * @deprecated since Symfony 5.3, use getUserIdentifier instead
     */
    public function getUsername(): string
    {
        return $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Returning a salt is only needed, if you are not using a modern
     * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
     *
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }
}
