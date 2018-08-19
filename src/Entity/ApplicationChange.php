<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ApplicationChangeRepository")
 */
class ApplicationChange
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;


    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @ORM\Column(type="datetime", length=255)
     */
    private $created;

    /**
     * @ORM\Column(type="datetime", length=255)
     */
    private $updated;

    /**
     * @ORM\Column(type="string")
     */
    private $auth_id;


    /**
     * @ORM\Column(type="string", length=255)
     */
    private $title;

    /**
     * @ORM\Column(type="integer")
     */
    private $application_id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\ApplicationChange", inversedBy="Application")
     */
    private $applicationChange;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Application", inversedBy="applicationChanges")
     */
    private $ApplicationChange;

    public function __construct()
    {
        $this->Application = new ArrayCollection();
        $this->updatedTimestamps();


    }

    public function getId(): ?int
    {
        return $this->id;
    }


    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getCreated(): ?\DateTimeInterface
    {
        return $this->created;
    }

    public function setCreated(\DateTimeInterface $created): self
    {
        $this->created = $created;

        return $this;
    }

    public function getUpdated(): ?\DateTimeInterface
    {
        return $this->updated;
    }

    public function setUpdated(?\DateTimeInterface $updated): self
    {
        $this->updated = $updated;

        return $this;
    }

    public function getAuthId(): ?string
    {
        return $this->auth_id;
    }

    public function setAuthId(string $auth_id): self
    {
        $this->auth_id = $auth_id;

        return $this;
    }

    /**
     *
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function updatedTimestamps()
    {
        $this->setUpdated(new \DateTime('now'));
        if ($this->getCreated() == null) {
            $this->setCreated(new \DateTime('now'));
        }
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

    public function getApplicationId(): ?int
    {
        return $this->application_id;
    }

    public function setApplicationId(int $application_id): self
    {
        $this->application_id = $application_id;

        return $this;
    }

    public function getApplicationChange(): ?self
    {
        return $this->applicationChange;
    }

    public function setApplicationChange(?self $applicationChange): self
    {
        $this->applicationChange = $applicationChange;

        return $this;
    }
}
