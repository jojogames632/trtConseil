<?php

namespace App\Entity;

use App\Repository\ValidJobRequestRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ValidJobRequestRepository::class)
 */
class ValidJobRequest
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Job::class, inversedBy="validJobRequests")
     * @ORM\JoinColumn(nullable=false)
     */
    private $job;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="validJobRequests")
     * @ORM\JoinColumn(nullable=false)
     */
    private $candidate;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getJob(): ?Job
    {
        return $this->job;
    }

    public function setJob(?Job $job): self
    {
        $this->job = $job;

        return $this;
    }

    public function getCandidate(): ?User
    {
        return $this->candidate;
    }

    public function setCandidate(?User $candidate): self
    {
        $this->candidate = $candidate;

        return $this;
    }
}
