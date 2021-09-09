<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @ORM\Table(name="`user`")
 * @UniqueEntity(fields={"email"}, message="There is already an account with this email")
 */
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     */
    private $email;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isActive;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $company;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $address;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $firstName;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $lastName;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $cvFilename;

    /**
     * @ORM\OneToMany(targetEntity=Job::class, mappedBy="recruiterId", orphanRemoval=true)
     */
    private $jobs;

    /**
     * @ORM\OneToMany(targetEntity=PendingJobRequest::class, mappedBy="candidate", orphanRemoval=true)
     */
    private $pendingJobRequests;

    /**
     * @ORM\OneToMany(targetEntity=ValidJobRequest::class, mappedBy="candidate", orphanRemoval=true)
     */
    private $validJobRequests;

    public function __construct()
    {
        $this->pendingJobRequests = new ArrayCollection();
        $this->validJobRequests = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @deprecated since Symfony 5.3, use getUserIdentifier instead
     */
    public function getUsername(): string
    {
        return (string) $this->email;
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

    public function getIsActive(): ?bool
    {
        return $this->isActive;
    }

    public function setIsActive(bool $isActive): self
    {
        $this->isActive = $isActive;

        return $this;
    }

    public function getCompany(): ?string
    {
        return $this->company;
    }

    public function setCompany(?string $company): self
    {
        $this->company = $company;

        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(?string $address): self
    {
        $this->address = $address;

        return $this;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(?string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(?string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getCvFilename(): ?string
    {
        return $this->cvFilename;
    }

    public function setCvFilename(?string $cvFilename): self
    {
        $this->cvFilename = $cvFilename;

        return $this;
    }

    /**
     * @return Collection|PendingJobRequest[]
     */
    public function getPendingJobRequests(): Collection
    {
        return $this->pendingJobRequests;
    }

    public function addPendingJobRequest(PendingJobRequest $pendingJobRequest): self
    {
        if (!$this->pendingJobRequests->contains($pendingJobRequest)) {
            $this->pendingJobRequests[] = $pendingJobRequest;
            $pendingJobRequest->setCandidate($this);
        }

        return $this;
    }

    public function removePendingJobRequest(PendingJobRequest $pendingJobRequest): self
    {
        if ($this->pendingJobRequests->removeElement($pendingJobRequest)) {
            // set the owning side to null (unless already changed)
            if ($pendingJobRequest->getCandidate() === $this) {
                $pendingJobRequest->setCandidate(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|ValidJobRequest[]
     */
    public function getValidJobRequests(): Collection
    {
        return $this->validJobRequests;
    }

    public function addValidJobRequest(ValidJobRequest $validJobRequest): self
    {
        if (!$this->validJobRequests->contains($validJobRequest)) {
            $this->validJobRequests[] = $validJobRequest;
            $validJobRequest->setCandidate($this);
        }

        return $this;
    }

    public function removeValidJobRequest(ValidJobRequest $validJobRequest): self
    {
        if ($this->validJobRequests->removeElement($validJobRequest)) {
            // set the owning side to null (unless already changed)
            if ($validJobRequest->getCandidate() === $this) {
                $validJobRequest->setCandidate(null);
            }
        }

        return $this;
    }
}
