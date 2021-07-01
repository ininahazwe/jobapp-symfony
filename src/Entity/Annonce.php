<?php

namespace App\Entity;

use App\Repository\AnnonceRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity(repositoryClass=AnnonceRepository::class)
 * @ORM\HasLifecycleCallbacks
 */
class Annonce
{
    use ResourceId;
    use Timestapable;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private ?string $name;

    /**
     * @ORM\Column(type="text")
     */
    private ?string $description;

    /**
     * @ORM\Column(type="boolean")
     */
    private ?bool $isActive;

    /**
     * @Gedmo\Slug(fields={"name"})
     * @ORM\Column(type="string", length=255)
     */
    private ?string $slug;

    /**
     * @ORM\ManyToOne(targetEntity=Dictionnaire::class, inversedBy="annonces")
     */
    private ?Dictionnaire $diplome;

    /**
     * @ORM\ManyToOne(targetEntity=Dictionnaire::class, inversedBy="annonces_experience")
     */
    private ?Dictionnaire $experience;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private ?string $reference;

    /**
     * @ORM\ManyToMany(targetEntity=Dictionnaire::class, inversedBy="annonces_type_contrat")
     * @ORM\JoinTable(name="annonces_contrat",
     *      joinColumns={@ORM\JoinColumn(name="annonce_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="dictionnaire_id", referencedColumnName="id")}
     *      )
     **/
    private Collection $type_contrat;

    /**
     * @ORM\ManyToMany(targetEntity=User::class, inversedBy="annonces_auteurs")
     * @ORM\JoinTable(name="annonces_auteurs",
     *      joinColumns={@ORM\JoinColumn(name="annonce_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id")}
     *      )
     **/
    private Collection $auteur;

    /**
     * @ORM\ManyToMany(targetEntity=Dictionnaire::class, inversedBy="annonces_location")
     * @ORM\JoinTable(name="annonces_location",
     *      joinColumns={@ORM\JoinColumn(name="annonce_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="dictionnaire_id", referencedColumnName="id")}
     *      )
     **/
    private Collection $location;

    /**
     * @ORM\ManyToOne(targetEntity=Entreprise::class, inversedBy="annonces_entreprise")
     */
    private ?Entreprise $entreprise;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $lien;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $adresse_email;

    /**
     * @ORM\ManyToOne(targetEntity=Offre::class, inversedBy="annonces")
     */
    private ?Offre $offre;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private ?\DateTimeInterface $dateLimiteCandidature;

    public function __construct()
    {
        $this->type_contrat = new ArrayCollection();
        $this->auteur = new ArrayCollection();
        $this->location = new ArrayCollection();
        $this->createdAt = new \DateTimeImmutable('now');
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
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

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function getDiplome(): ?Dictionnaire
    {
        return $this->diplome;
    }

    public function setDiplome(?Dictionnaire $diplome): self
    {
        $this->diplome = $diplome;

        return $this;
    }

    public function getExperience(): ?Dictionnaire
    {
        return $this->experience;
    }

    public function setExperience(?Dictionnaire $experience): self
    {
        $this->experience = $experience;

        return $this;
    }

    public function getReference(): ?string
    {
        return $this->reference;
    }

    public function setReference(string $reference): self
    {
        $this->reference = $reference;

        return $this;
    }

    /**
     * @return Collection
     */
    public function getTypeContrat(): Collection
    {
        return $this->type_contrat;
    }

    public function addTypeContrat(Dictionnaire $typeContrat): self
    {
        if (!$this->type_contrat->contains($typeContrat)) {
            $this->type_contrat[] = $typeContrat;
        }

        return $this;
    }

    public function removeTypeContrat(Dictionnaire $typeContrat): self
    {
        $this->type_contrat->removeElement($typeContrat);

        return $this;
    }

    /**
     * @return Collection
     */
    public function getAuteur(): Collection
    {
        return $this->auteur;
    }

    public function addAuteur(User $auteur): self
    {
        if (!$this->auteur->contains($auteur)) {
            $this->auteur[] = $auteur;
        }

        return $this;
    }

    public function removeAuteur(User $auteur): self
    {
        $this->auteur->removeElement($auteur);

        return $this;
    }

    /**
     * @return Collection
     */
    public function getLocation(): Collection
    {
        return $this->location;
    }

    public function addLocation(Dictionnaire $location): self
    {
        if (!$this->location->contains($location)) {
            $this->location[] = $location;
        }

        return $this;
    }

    public function removeLocation(Dictionnaire $location): self
    {
        $this->location->removeElement($location);

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

    public function getLien(): ?string
    {
        return $this->lien;
    }

    public function setLien(?string $lien): self
    {
        $this->lien = $lien;

        return $this;
    }

    public function getAdresseEmail(): ?string
    {
        return $this->adresse_email;
    }

    public function setAdresseEmail(?string $adresse_email): self
    {
        $this->adresse_email = $adresse_email;

        return $this;
    }

    public function getOffre(): ?Offre
    {
        return $this->offre;
    }

    public function setOffre(?Offre $offre): self
    {
        $this->offre = $offre;

        return $this;
    }

    public function getDateLimiteCandidature(): ?\DateTimeInterface
    {
        return $this->dateLimiteCandidature;
    }

    public function setDateLimiteCandidature(?\DateTimeInterface $dateLimiteCandidature): self
    {
        $this->dateLimiteCandidature = $dateLimiteCandidature;

        return $this;
    }

    public function isActive()
    {
        if ($this->getIsActive() == 1 ){
            return true;
        }
        return false;
    }
}
