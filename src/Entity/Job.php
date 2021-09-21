<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=JobRepository::class)
 */
class Job
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank
     * @Assert\Length(max=255)
     */
    private $title;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Length(max=255)
     */
    private $place;

    /**
     * @ORM\Column(type="time")
     */
    private $scheduleStart;

    /**
     * @ORM\Column(type="time")
     */
    private $scheduleEnd;

    /**
     * @ORM\Column(type="integer")
     * @Assert\PositiveOrZero
     */
    private $salary;

    /**
     * @ORM\Column(type="integer")
     * @Assert\PositiveOrZero
     */
    private $yearExperienceRequired;

    /**
     * @ORM\Column(type="text")
     * @Assert\NotBlank
     * @Assert\Length(max=2000)
     */
    private $description;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isValid;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="jobs")
     * @ORM\JoinColumn(nullable=false)
     */
    private $recruiter;

    /**
     * @ORM\OneToMany(targetEntity=PendingJobRequest::class, mappedBy="job", orphanRemoval=true)
     */
    private $pendingJobRequests;

    /**
     * @ORM\OneToMany(targetEntity=ValidJobRequest::class, mappedBy="job", orphanRemoval=true)
     */
    private $validJobRequests;

    public function __construct()
    {
        $this->candidateJobs = new ArrayCollection();
        $this->pendingJobRequests = new ArrayCollection();
        $this->validJobRequests = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getPlace(): ?string
    {
        return $this->place;
    }

    public function setPlace(string $place): self
    {
        $this->place = $place;

        return $this;
    }

    public function getScheduleStart(): ?\DateTimeInterface
    {
        return $this->scheduleStart;
    }

    public function setScheduleStart(\DateTimeInterface $scheduleStart): self
    {
        $this->scheduleStart = $scheduleStart;

        return $this;
    }

    public function getScheduleEnd(): ?\DateTimeInterface
    {
        return $this->scheduleEnd;
    }

    public function setScheduleEnd(\DateTimeInterface $scheduleEnd): self
    {
        $this->scheduleEnd = $scheduleEnd;

        return $this;
    }

    public function getSalary(): ?int
    {
        return $this->salary;
    }

    public function setSalary(int $salary): self
    {
        $this->salary = $salary;

        return $this;
    }

    public function getYearExperienceRequired(): ?int
    {
        return $this->yearExperienceRequired;
    }

    public function setYearExperienceRequired(int $yearExperienceRequired): self
    {
        $this->yearExperienceRequired = $yearExperienceRequired;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getIsValid(): ?bool
    {
        return $this->isValid;
    }

    public function setIsValid(bool $isValid): self
    {
        $this->isValid = $isValid;

        return $this;
    }

    public function getRecruiter(): ?User
    {
        return $this->recruiter;
    }

    public function setRecruiter(?User $recruiter): self
    {
        $this->recruiter = $recruiter;

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
            $pendingJobRequest->setJob($this);
        }

        return $this;
    }

    public function removePendingJobRequest(PendingJobRequest $pendingJobRequest): self
    {
        if ($this->pendingJobRequests->removeElement($pendingJobRequest)) {
            // set the owning side to null (unless already changed)
            if ($pendingJobRequest->getJob() === $this) {
                $pendingJobRequest->setJob(null);
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
            $validJobRequest->setJob($this);
        }

        return $this;
    }

    public function removeValidJobRequest(ValidJobRequest $validJobRequest): self
    {
        if ($this->validJobRequests->removeElement($validJobRequest)) {
            // set the owning side to null (unless already changed)
            if ($validJobRequest->getJob() === $this) {
                $validJobRequest->setJob(null);
            }
        }

        return $this;
    }
}
