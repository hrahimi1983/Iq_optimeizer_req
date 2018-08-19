<?php

namespace App\Entity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ApplicationRepository")
 */
class Application extends Controller
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $auth_id;


    /**
     * @ORM\Column(type="datetime")
     */
    private $created;

    /**
     * @ORM\Column(type="datetime")
     */
    private $updated;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\ApplicationChange", mappedBy="ApplicationChange" , cascade={"persist", "remove"})
     */
    private $applicationChanges;


    public function __construct ()
    {
        $this->updatedTimestamps();
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


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }


    public function getAuthId(): ?int
    {
        return $this->auth_id;
    }

    public function setAuthId(?int $auth_id): self
    {
        $this->auth_id = $auth_id;

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

    public function setUpdated(\DateTimeInterface $updated): self
    {
        $this->updated = $updated;

        return $this;
    }

    /**
     * @return Collection|ApplicationChange[]
     */
    public function getApplicationChanges(): Collection
    {
        return $this->applicationChanges;
    }

    public function addApplicationChange(ApplicationChange $applicationChange): self
    {
            $this->applicationChanges[] = $applicationChange;
            $applicationChange->setApplicationChange($applicationChange);
            return $this;
    }

    public function removeApplicationChange(ApplicationChange $applicationChange): self
    {
        if ($this->applicationChanges->contains($applicationChange)) {
            $this->applicationChanges->removeElement($applicationChange);
            // set the owning side to null (unless already changed)
            if ($applicationChange->getApplicationChange() === $this) {
                $applicationChange->setApplicationChange(null);
            }
        }

        return $this;
    }
}
