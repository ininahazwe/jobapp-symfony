<?php

namespace App\Entity;

use App\Repository\UserRepository;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @UniqueEntity(fields={"email"}, message="Impossible de créer un compte utilisateur avec cet email")
 */
class User implements UserInterface
{
    use ResourceId;
    use Timestapable;

     /**
     * @ORM\Column(type="string", length=180, unique=true)
     */
    private string $email;

    /**
     * @ORM\Column(type="json")
     * @var array<string>
     */
    private array $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private string $password;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private string $firstname;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private string $lastname;

    /**
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected ?\DateTime $lastConnexionAt;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $telephone;

    /**
     * @ORM\Column(type="boolean")
     */
    private bool $isTermsClients;

    /**
     * @ORM\OneToOne(targetEntity=Profile::class, mappedBy="user", cascade={"persist", "remove"})
     */
    private ?Profile $profile;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $forgotPasswordToken;

    private ?DateTimeImmutable $lastConnextionAt;

    /**
     * @return string|null
     */
    public function getForgotPasswordToken(): ?string
    {
        return $this->forgotPasswordToken;
    }

    /**
     * @param string|null $forgotPasswordToken
     */
    public function setForgotPasswordToken(?string $forgotPasswordToken): void
    {
        $this->forgotPasswordToken = $forgotPasswordToken;
    }

    /**
     * @return DateTimeImmutable|null
     */
    public function getForgotPasswordTokenRequestedAt(): ?DateTimeImmutable
    {
        return $this->forgotPasswordTokenRequestedAt;
    }

    /**
     * @param DateTimeImmutable|null $forgotPasswordTokenRequestedAt
     */
    public function setForgotPasswordTokenRequestedAt(?DateTimeImmutable $forgotPasswordTokenRequestedAt): void
    {
        $this->forgotPasswordTokenRequestedAt = $forgotPasswordTokenRequestedAt;
    }

    /**
     * @return DateTimeImmutable|null
     */
    public function getForgotPasswordTokenMustBeVerifiedBefore(): ?DateTimeImmutable
    {
        return $this->forgotPasswordTokenMustBeVerifiedBefore;
    }

    /**
     * @param DateTimeImmutable|null $forgotPasswordTokenMustBeVerifiedBefore
     */
    public function setForgotPasswordTokenMustBeVerifiedBefore(?DateTimeImmutable $forgotPasswordTokenMustBeVerifiedBefore): void
    {
        $this->forgotPasswordTokenMustBeVerifiedBefore = $forgotPasswordTokenMustBeVerifiedBefore;
    }

    /**
     * @return DateTimeImmutable|null
     */
    public function getForgotPasswordTokenVerifiedAt(): ?DateTimeImmutable
    {
        return $this->forgotPasswordTokenVerifiedAt;
    }

    /**
     * @param DateTimeImmutable|null $forgotPasswordTokenVerifiedAt
     */
    public function setForgotPasswordTokenVerifiedAt(?DateTimeImmutable $forgotPasswordTokenVerifiedAt): void
    {
        $this->forgotPasswordTokenVerifiedAt = $forgotPasswordTokenVerifiedAt;
    }

    /**
     * @ORM\Column(type="datetime_immutable", nullable=true)
     */
    private ?DateTimeImmutable $forgotPasswordTokenRequestedAt;

    /**
     * @ORM\Column(type="datetime_immutable", nullable=true)
     */
    private ?DateTimeImmutable $forgotPasswordTokenMustBeVerifiedBefore;

    /**
     * @ORM\Column(type="datetime_immutable", nullable=true)
     */
    private ?DateTimeImmutable $forgotPasswordTokenVerifiedAt;

    /**
     * @ORM\OneToMany(targetEntity=Annonce::class, mappedBy="author")
     */
    private Collection $annonces;

    /**
     * @ORM\OneToMany(targetEntity=Candidature::class, mappedBy="candidat")
     */
    private Collection $candidatures;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private ?string $activation_token;

    /**
     * @ORM\ManyToMany(targetEntity=Entreprise::class, mappedBy="recruteurs")
     */
    private Collection $entreprises;

    /**
     * @ORM\ManyToMany(targetEntity=Entreprise::class, mappedBy="super_recruteurs")
     */
    private Collection $recruteurs_entreprise;

    /**
     * @ORM\OneToOne(targetEntity=File::class, inversedBy="user", cascade={"persist", "remove"})
     */
    private ?File $photo;

    public function __construct()
    {
        $this->isTermsClients = false;
        $this->roles = ['ROLE_CANDIDAT'];
        $this->lastConnexionAt = new \DateTime('now');
        $this->annonces = new ArrayCollection();
        $this->candidatures = new ArrayCollection();
        $this->createdAt = new DateTimeImmutable('now');
        $this->entreprises = new ArrayCollection();
        $this->recruteurs_entreprise = new ArrayCollection();
    }

    public function getEmail(): string
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
        $roles[] = 'ROLE_CANDIDAT';

        return array_unique($roles);
    }

    /**
     * set the user role
     *
     * @param array<string> $roles
     * @return self
     */
    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string) $this->password;
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
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): self
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getLastConnexionAt(): ?\DateTimeInterface
    {
        return $this->lastConnextionAt;
    }

    public function getTelephone(): ?string
    {
        return $this->telephone;
    }

    public function setTelephone(?string $telephone): self
    {
        $this->telephone = $telephone;

        return $this;
    }

    public function getIsTermsClients(): ?bool
    {
        return $this->isTermsClients;
    }

    public function setIsTermsClients(bool $isTermsClients): self
    {
        $this->isTermsClients = $isTermsClients;

        return $this;
    }

    public function getProfile(): ?Profile
    {
        return $this->profile;
    }

    public function setProfile(?Profile $profile): self
    {
        // unset the owning side of the relation if necessary
        if ($profile === null && $this->profile !== null) {
            $this->profile->setUser(null);
        }

        // set the owning side of the relation if necessary
        if ($profile !== null && $profile->getUser() !== $this) {
            $profile->setUser($this);
        }

        $this->profile = $profile;

        return $this;
    }

    public function __toString()
    {
        return $this->getEmail();
    }

    public function getFullname(): string
    {
        return $this->getFirstName().' '.$this->getLastName();
    }

    public function isCandidat(): bool
    {
        $role = "ROLE_CANDIDAT";
        return $this->checkRoles($role);
    }

    public function isRecruteur(): bool
    {
        $role = "ROLE_RECRUTEUR";
        return $this->checkRoles($role);
    }

    public function isSuperRecruteur(): bool
    {
        $role = "ROLE_SUPER_RECRUTEUR";
        return $this->checkRoles($role);

    }

    public function isCommunicant(): bool
    {
        $role = "ROLE_COMMUNICANT";
        return $this->checkRoles($role);
    }

    public function isSuperAdmin(): bool
    {
        $role = "ROLE_SUPER_ADMIN_HANDICV";
        return $this->checkRoles($role);
    }

    public function getRoleName():string
    {
        if ($this->isSuperAdmin()){
            return 'Super Administrateur';
        }else if($this->isSuperRecruteur()){
            return 'Super Recruteur';
        }else if($this->isRecruteur()){
            return 'Recruteur';
        }else if($this->isCandidat()){
            return 'Candidat';
        }else if($this->isCommunicant()){
            return 'Communicant';
        }else{
            return 'Non renseigné';
        }
    }
    public function checkRoles($role): bool
    {
        foreach($this->roles as $item)
        {
            if($item == $role)
            {
                return true;
            }
        }
        return false;
    }

    /**
     * @return Collection
     */
    public function getAnnonces(): Collection
    {
        return $this->annonces;
    }

    public function addAnnonce(Annonce $annonce): self
    {
        if (!$this->annonces->contains($annonce)) {
            $this->annonces[] = $annonce;
            $annonce->setAuthor($this);
        }

        return $this;
    }

    public function removeAnnonce(Annonce $annonce): self
    {
        if ($this->annonces->removeElement($annonce)) {
            // set the owning side to null (unless already changed)
            if ($annonce->getAuthor() === $this) {
                $annonce->setAuthor(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection
     */
    public function getCandidatures(): Collection
    {
        return $this->candidatures;
    }

    public function addCandidature(Candidature $candidature): self
    {
        if (!$this->candidatures->contains($candidature)) {
            $this->candidatures[] = $candidature;
            $candidature->setCandidat($this);
        }

        return $this;
    }

    public function removeCandidature(Candidature $candidature): self
    {
        if ($this->candidatures->removeElement($candidature)) {
            // set the owning side to null (unless already changed)
            if ($candidature->getCandidat() === $this) {
                $candidature->setCandidat(null);
            }
        }

        return $this;
    }

    public function getActivationToken(): ?string
    {
        return $this->activation_token;
    }

    public function setActivationToken(?string $activation_token): self
    {
        $this->activation_token = $activation_token;

        return $this;
    }

    /**
     * @return Collection
     */
    public function getEntreprises(): Collection
    {
        return $this->entreprises;
    }

    public function addEntreprise(Entreprise $entreprise): self
    {
        if (!$this->entreprises->contains($entreprise)) {
            $this->entreprises[] = $entreprise;
            $entreprise->addRecruteur($this);
        }

        return $this;
    }

    public function removeEntreprise(Entreprise $entreprise): self
    {
        if ($this->entreprises->removeElement($entreprise)) {
            $entreprise->removeRecruteur($this);
        }

        return $this;
    }

    /**
     * @return Collection
     */
    public function getRecruteursEntreprise(): Collection
    {
        return $this->recruteurs_entreprise;
    }

    public function addRecruteursEntreprise(Entreprise $recruteursEntreprise): self
    {
        if (!$this->recruteurs_entreprise->contains($recruteursEntreprise)) {
            $this->recruteurs_entreprise[] = $recruteursEntreprise;
            $recruteursEntreprise->addSuperRecruteur($this);
        }

        return $this;
    }

    public function removeRecruteursEntreprise(Entreprise $recruteursEntreprise): self
    {
        if ($this->recruteurs_entreprise->removeElement($recruteursEntreprise)) {
            $recruteursEntreprise->removeSuperRecruteur($this);
        }

        return $this;
    }

    public function getPhoto(): ?File
    {
        return $this->photo;
    }

    public function setPhoto($photo): self
    {
        $this->photo = $photo;

        return $this;
    }
}