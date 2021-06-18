<?php

namespace App\Entity;

use App\Repository\ModeleOffreCommercialeRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity(repositoryClass=ModeleOffreCommercialeRepository::class)
 */
class ModeleOffreCommerciale
{
    use ResourceId;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private string $name;

    /**
     * @ORM\Column(type="integer", length=255, nullable=true)
     */
    private ?int $nombre_offres;

    /**
     * @ORM\Column(type="boolean")
     */
    private bool $isCvTheque;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private string $dureeContrat;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private string $prix;

    /**
     * @ORM\Column(type="integer", length=255, nullable=true)
     */
    private ?int $nombre_recruteurs;

    /**
     * @ORM\OneToMany(targetEntity=Offre::class, mappedBy="modele_offre_commerciale", cascade={"persist", "remove"})
     */
    private ?Collection $offre;

    /**
     * @ORM\Column(type="string", length=255)
     * @Gedmo\Slug(fields={"name"})
     */
    private ?string $slug;

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

    public function getNombreOffres(): ?string
    {
        return $this->nombre_offres;
    }

    public function setNombreOffres(string $nombre_offres): self
    {
        $this->nombre_offres = $nombre_offres;

        return $this;
    }

    public function getIsCvTheque(): ?bool
    {
        return $this->isCvTheque;
    }

    public function setIsCvTheque(bool $isCvTheque): self
    {
        $this->isCvTheque = $isCvTheque;

        return $this;
    }

    public function getDureeContrat(): ?string
    {
        return $this->dureeContrat;
    }

    public function setDureeContrat(string $dureeContrat): self
    {
        $this->dureeContrat = $dureeContrat;

        return $this;
    }

    public function getPrix(): ?string
    {
        return $this->prix;
    }

    public function setPrix(string $prix): self
    {
        $this->prix = $prix;

        return $this;
    }

    public function getNombreRecruteurs(): ?string
    {
        return $this->nombre_recruteurs;
    }

    public function setNombreRecruteurs(?string $nombre_recruteurs): self
    {
        $this->nombre_recruteurs = $nombre_recruteurs;

        return $this;
    }

    public function getOffre(): ?Offre
    {
        return $this->offre;
    }

    public function setOffre(?Offre $offre): self
    {
        // unset the owning side of the relation if necessary
        if ($offre === null && $this->offre !== null) {
            $this->offre->setModeleOffreCommerciale(null);
        }

        // set the owning side of the relation if necessary
        if ($offre !== null && $offre->getModeleOffreCommerciale() !== $this) {
            $offre->setModeleOffreCommerciale($this);
        }

        $this->offre = $offre;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }
}
