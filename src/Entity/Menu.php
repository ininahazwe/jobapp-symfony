<?php

namespace App\Entity;

use App\Repository\MenuRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=MenuRepository::class)
 */
class Menu
{
    const TYPE_MENU_CANDIDAT = 1;
    const TYPE_MENU_RECRUTEUR = 2;
    const TYPE_MENU_FOOTER = 3;
    const TYPE_MENU_CONTENU = 4;
    const TYPE_MENU_PRESENTATION_RS = 5;
    const TYPE_MENU_TWITTER = 6;
    const TYPE_MENU_FACEBOOK = 7;
    const TYPE_MENU_INSTAGRAM = 8;
    const TYPE_MENU_SKYPE = 9;
    const TYPE_MENU_LINKEDIN = 10;
    const TYPE_MENU_YOUTUBE = 11;

    const NIVEAU_MENU_1 = 1;
    const NIVEAU_MENU_2 = 2;
    const NIVEAU_MENU_3 = 3;
    const NIVEAU_MENU_4 = 4;

    use ResourceId;
    /**
     * @ORM\Column(type="string", length=255)
     */
    private string $name;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private string $link;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private string $display_order;

    /**
     * @ORM\OneToOne(targetEntity=Profile::class, mappedBy="menu", cascade={"persist", "remove"})
     * @ORM\Column(type="boolean", nullable=true)
     */
    private bool $isParent;

    /**
     *
     * @ORM\Column(type="boolean", nullable=true)
     */
    private bool $isChild;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private string $type;

    /**
     * @ORM\OneToOne(targetEntity=Menu::class, cascade={"persist", "remove"})
     */
    private ?Menu $child_menu;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private ?int $niveau;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private ?string $contenu;

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getLink(): ?string
    {
        return $this->link;
    }

    public function setLink(?string $link): self
    {

        if (!$link){
            $this->link = "#";
        }else{
            $this->link = $link;
        }
        return $this;
    }

    public function getDisplayOrder(): ?string
    {
        return $this->display_order;
    }

    public function setDisplayOrder(?string $display_order): self
    {

        if (!$display_order){
            $this->display_order = "0";
        }else{
            $this->display_order = $display_order;
        }

        return $this;
    }

    public function getIsParent(): ?bool
    {
        return $this->isParent;
    }

    public function setIsParent(bool $isParent): self
    {
        $this->isParent = $isParent;

        return $this;
    }

    public function getIsChild(): ?bool
    {
        return $this->isChild;
    }

    public function setIsChild(bool $isChild): self
    {
        $this->isChild = $isChild;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }


    /**
     * @return int[]
     */
    public static function getTypeList(): array
    {
        return array(
            'Menu Candidat' => Menu::TYPE_MENU_CANDIDAT,
            'Menu Recruteur' => Menu::TYPE_MENU_RECRUTEUR,
            'Menu Footer' => Menu::TYPE_MENU_FOOTER,
            'Menu Contenu' => Menu::TYPE_MENU_CONTENU,
            'Menu Présentation RS' => Menu::TYPE_MENU_PRESENTATION_RS,
            'Menu Twitter' => Menu::TYPE_MENU_TWITTER,
            'Menu Instagram' => Menu::TYPE_MENU_INSTAGRAM,
            'Menu Skype' => Menu::TYPE_MENU_SKYPE,
            'Menu Linkedin' => Menu::TYPE_MENU_LINKEDIN,
            'Menu Youtube' => Menu::TYPE_MENU_YOUTUBE,
        );
    }

    /**
     * @return int[]
     */
    public static function getNiveauList(): array
    {
        return array(
            'Niveau 1' => Menu::NIVEAU_MENU_1,
            'Niveau 2' => Menu::NIVEAU_MENU_2,
            'Niveau 3' => Menu::NIVEAU_MENU_3,
            'Niveau 4' => Menu::NIVEAU_MENU_4,
        );
    }

    public function getChildMenu(): ?self
    {
        return $this->child_menu;
    }

    public function setChildMenu(?self $child_menu): self
    {
        $this->child_menu = $child_menu;

        return $this;
    }

    public function getNiveau(): ?int
    {
        return $this->niveau;
    }

    public function setNiveau(?int $niveau): self
    {
        $this->niveau = $niveau;

        return $this;
    }
    public function __toString(): string
    {
        // TODO: Implement __toString() method.
        return $this->name;
    }
    public function getNameNiveau(): string
    {
        if ($this->niveau == self::NIVEAU_MENU_1) {
            return $this->name . " (Niveau 1)";
        } else if ($this->niveau == self::NIVEAU_MENU_2) {
            return "- " . $this->name . " (Niveau 2)";
        } else if ($this->niveau == self::NIVEAU_MENU_3) {
            return "-- " . $this->name . " (Niveau 3)";
        }else if ($this->niveau == self::NIVEAU_MENU_4) {
            return "--- " . $this->name . " (Niveau 4)";
        }else{
            return " " . $this->name . " (Niveau 1)";
        }
    }

    public function getContenu(): ?string
    {
        return $this->contenu;
    }

    public function setContenu(?string $contenu): self
    {
        $this->contenu = $contenu;

        return $this;
    }
}
