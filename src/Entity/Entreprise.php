<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\EntrepriseRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity(repositoryClass=EntrepriseRepository::class)
 */
class Entreprise
{
    use ResourceId;
    use Timestapable;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private string $name;

    /**
     * @ORM\Column(type="text")
     */
    private string $description;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private string $address;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private string $city;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private string $zipcode;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private string $secteur;

    /**
     * @Gedmo\Slug(fields={"name"})
     * @ORM\Column(type="string", length=255)
     */
    private string $slug;

    /**
     * @ORM\OneToMany(targetEntity=Offre::class, mappedBy="entreprise")
     */
    private Collection $Offres;

    /**
     * @ORM\ManyToMany(targetEntity=User::class, inversedBy="entreprises")
     * @ORM\JoinTable(name="entreprise_recruteur",
     *      joinColumns={@ORM\JoinColumn(name="entreprise_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id")}
     *      )
     **/
    private Collection $recruteurs;

    /**
     * @ORM\ManyToMany(targetEntity=User::class, inversedBy="recruteurs_entreprise")
     * @ORM\JoinTable(name="entreprise_super_recruteur",
     *      joinColumns={@ORM\JoinColumn(name="entreprise_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id")}
     *      )
     **/
    private Collection $super_recruteurs;

    /**
     * @ORM\OneToMany(targetEntity=File::class, mappedBy="entreprise", orphanRemoval=true, cascade={"persist"})
     */
    private Collection $logo;

    public function __construct()
    {
        $this->Offres = new ArrayCollection();
        $this->createdAt = new \DateTimeImmutable();
        $this->recruteurs = new ArrayCollection();
        $this->super_recruteurs = new ArrayCollection();
        $this->logo = new ArrayCollection();
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
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

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(string $address): self
    {
        $this->address = $address;

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

    public function getSecteur(): ?string
    {
        return $this->secteur;
    }

    public function setSecteur(string $secteur): self
    {
        $this->secteur = $secteur;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    /**
     * @return Collection
     */
    public function getOffres(): Collection
    {
        return $this->Offres;
    }

    public function addOffre(Offre $Offre): self
    {
        if (!$this->Offres->contains($Offre)) {
            $this->Offres[] = $Offre;
            $Offre->setEntreprise($this);
        }

        return $this;
    }

    public function removeOffre(Offre $Offre): self
    {
        if ($this->Offres->removeElement($Offre)) {
            // set the owning side to null (unless already changed)
            if ($Offre->getEntreprise() === $this) {
                $Offre->setEntreprise(null);
            }
        }

        return $this;
    }
    public function __toString()
    {
        return $this->name;
    }

    /**
     * @return Collection
     */
    public function getRecruteurs(): Collection
    {
        return $this->recruteurs;
    }

    public function addRecruteur(User $recruteur): self
    {
        if (!$this->recruteurs->contains($recruteur)) {
            $this->recruteurs[] = $recruteur;
        }

        return $this;
    }

    public function removeRecruteur(User $recruteur): self
    {
        $this->recruteurs->removeElement($recruteur);

        return $this;
    }

    /**
     * @return Collection
     */
    public function getSuperRecruteurs(): Collection
    {
        return $this->super_recruteurs;
    }

    public function addSuperRecruteur(User $superRecruteur): self
    {
        if (!$this->super_recruteurs->contains($superRecruteur)) {
            $this->super_recruteurs[] = $superRecruteur;
        }

        return $this;
    }

    public function removeSuperRecruteur(User $superRecruteur): self
    {
        $this->super_recruteurs->removeElement($superRecruteur);

        return $this;
    }

    /**
     * @return Collection
     */
    public function getLogo(): Collection
    {
        return $this->logo;
    }

    public function addLogo(File $logo): self
    {
        if (!$this->logo->contains($logo)) {
            $this->logo[] = $logo;
            $logo->setEntreprise($this);
        }

        return $this;
    }

    public function removeLogo(File $logo): self
    {
        if ($this->logo->removeElement($logo)) {
            // set the owning side to null (unless already changed)
            if ($logo->getEntreprise() === $this) {
                $logo->setEntreprise(null);
            }
        }

        return $this;
    }
}
