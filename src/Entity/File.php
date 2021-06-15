<?php

namespace App\Entity;

use App\Repository\FileRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=FileRepository::class)
 */
class File
{
    use ResourceId;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private ?string $name;

    /**
     * @ORM\ManyToOne(targetEntity=Entreprise::class, inversedBy="logo")
     */
    private ?Entreprise $entreprise;

    /**
     * @ORM\OneToOne(targetEntity=User::class, mappedBy="photo", cascade={"persist", "remove"})
     */
    private ?User $user;

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getEntreprise(): ?Entreprise
    {
        return $this->entreprise;
    }

    public function setEntreprise(?Entreprise $entreprise): self
    {
        $this->entreprise = $entreprise;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        // unset the owning side of the relation if necessary
        if ($user === null && $this->user !== null) {
            $this->user->setPhoto(null);
        }

        // set the owning side of the relation if necessary
        if ($user !== null && $user->getPhoto() !== $this) {
            $user->setPhoto($this);
        }

        $this->user = $user;

        return $this;
    }
}
