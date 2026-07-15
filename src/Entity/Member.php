<?php
namespace App\Entity;

use App\Repository\MemberRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use OpenApi\Attributes as OA;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: MemberRepository::class)]
#[UniqueEntity(fields: ['email'], message: 'Cette adresse email est déjà associée à un compte membre.')]
#[OA\Schema(
    description: "Modèle représentant un membre adhérent de la bibliothèque."
)]
class Member extends BaseEntity
{
    public const MEMBER_ID = 'memberId';
    public const MEMBER_NAME = 'name';


    #[ORM\Column(type: Types::BIGINT,unique: true)]
    #[Groups(["loan_read","loan_write"])]
    private ?int $memberId = null;

    #[ORM\Column(length: 100)]
    #[Assert\NotBlank(message: "Le nom du membre ne peut pas être vide.")]
    #[Assert\Length(
        min: 2,
        max: 100,
        minMessage: "Le nom doit contenir au moins {{ limit }} caractères."
    )]
    #[Groups(['loan_read', 'member_read', 'member_write'])]
    #[OA\Property(description: "Nom complet (Prénom Nom) du membre.", example: "Jean Dupont")]
    private ?string $name = null;

    #[ORM\Column(length: 180, unique: true)]
    #[Assert\NotBlank(message: "L'adresse email est obligatoire.")]
    #[Assert\Email(message: "L'adresse email '{{ value }}' n'est pas un email valide.")]
    #[Groups(['loan_read', 'member_read', 'member_write'])]
    #[OA\Property(description: "Adresse email unique du membre.", example: "jean.dupont@email.com")]
    private ?string $email = null;

    #[ORM\Column]
    #[Groups(['member_read', 'member_write'])]
    #[OA\Property(description: "Indique si le compte du membre est actif (autorisé à emprunter) ou suspendu.", example: true)]
    private bool $isActive = true;


    public function __construct()
    {
        $this->isActive = true;
    }

    // =========================================================================
    // GETTERS & SETTERS
    // =========================================================================



    final public function getName(): ?string
    {
        return $this->name;
    }

    final public function setName(string $name): self
    {
        $this->name = $name;
        return $this;
    }

    final public function getEmail(): ?string
    {
        return $this->email;
    }

    final public function setEmail(string $email): self
    {
        $this->email = $email;
        return $this;
    }

    /**
     * @return int|null
     */
    final public function getMemberId(): ?int
    {
        return $this->memberId;
    }

    /**
     * @param int|null $memberId
     */
    final public function setMemberId(?int $memberId): static
    {
        $this->memberId = $memberId;
        return $this;
    }


    final public function isIsActive(): bool
    {
        return $this->isActive;
    }

    final public function setIsActive(bool $isActive): self
    {
        $this->isActive = $isActive;
        return $this;
    }
}
