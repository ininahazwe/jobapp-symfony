<?php

namespace App\Entity;

use App\Repository\ProfileRepository;
use Doctrine\ORM\Mapping as ORM;


/**
 * @ORM\Entity(repositoryClass=ProfileRepository::class)
 */
class Profile
{
    use ResourceId;
    use Timestapable;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private ?string $description;

     /**
     * @ORM\Column(type="datetime")
     */
    private $birthdate;

    /**
     * @ORM\Column(type="boolean")
     */
    private bool $isRqth;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private string $city;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private string $zipcode;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private string $diplome;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private string $experiences;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private string $zoneDeRecherche;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private string $metiers;

    /**
     * @ORM\Column(type="boolean")
     */
    private bool $isVisible;

    /**
     * @ORM\Column(type="boolean")
     */
    private bool $isAmenagement;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private string $cv;

    /**
     * @ORM\OneToOne(targetEntity=User::class, inversedBy="profile", cascade={"persist", "remove"})
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    private User $user;

    public function __construct()
    {
        $this->isVisible = false;
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

    public function getBirthdate()
    {
        return $this->birthdate;
    }

    public function setBirthdate($birthdate): self
    {
        if (isset($birthdate)) {
            $this->birthdate = $birthdate;
        }

        return $this;
    }

    public function getIsRqth(): ?bool
    {
        return $this->isRqth;
    }

    public function setIsRqth(bool $isRqth): self
    {
        $this->isRqth = $isRqth;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(string $city): self
    {
        $this->city = $city;

        return $this;
    }

    public function getZipcode(): ?string
    {
        return $this->zipcode;
    }

    public function setZipcode(string $zipcode): self
    {
        $this->zipcode = $zipcode;

        return $this;
    }

    public function getDiplome(): ?string
    {
        return $this->diplome;
    }

    public function setDiplome(?string $diplome): self
    {
        if (isset($diplome)) {
            $this->diplome = $diplome;
        }

        return $this;
    }

    public function getExperiences(): ?string
    {
        return $this->experiences;
    }

    public function setExperiences(?string $experiences): self
    {
        if (isset($experiences)) {
            $this->experiences = $experiences;
        }

        return $this;
    }

    public function getZoneDeRecherche(): ?string
    {
        return $this->zoneDeRecherche;
    }

    public function setZoneDeRecherche(?string $zoneDeRecherche): self
    {
        if (!empty($zoneDeRecherche)) {
            $this->zoneDeRecherche = $zoneDeRecherche;
        }

        return $this;
    }

    public function getMetiers(): ?string
    {
        return $this->metiers;
    }

    public function setMetiers(?string $metiers): self
    {
        if (isset($metiers)) {
            $this->metiers = $metiers;
        }

        return $this;
    }

    public function getIsVisible(): ?bool
    {
        return $this->isVisible;
    }

    public function setIsVisible(bool $isVisible): self
    {
        $this->isVisible = $isVisible;

        return $this;
    }

    public function getIsAmenagement(): ?bool
    {
        return $this->isAmenagement;
    }

    public function setIsAmenagement(bool $isAmenagement): self
    {
        $this->isAmenagement = $isAmenagement;

        return $this;
    }

    public function getCv(): ?string
    {
        return $this->cv;
    }

    public function setCv(?string $cv): self
    {
        if (isset($cv)) {
            $this->cv = $cv;
        }

        return $this;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        if (!empty($user)) {
            $this->user = $user;
        }

        return $this;
    }
}
