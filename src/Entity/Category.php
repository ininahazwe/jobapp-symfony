<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="category")
 * use repository for handy tree functions
 */
class Category
{

    const UNPUBLISHED = 0;
    const PUBLISHED = 1;

   use ResourceId;
    /**
     * @ORM\Column(name="title", type="string", length=128)
     */
    private string $title;

    /**
     * @ORM\Column(name="lft", type="integer")
     */
    private int $lft;

    /**
     * @ORM\Column(name="lvl", type="integer")
     */
    private int $lvl;

    /**
     * @ORM\Column(name="rgt", type="integer")
     */
    private int $rgt;

    /**
     * @ORM\Column(name="root", type="integer", nullable=true)
     */
    private int $root;

    /**
     * @ORM\ManyToOne(targetEntity="Category", inversedBy="children")
     * @ORM\JoinColumn(name="parent_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $parent;

    /**
     * @ORM\OneToMany(targetEntity="Category", mappedBy="parent")
     * @ORM\OrderBy({"lft" = "ASC"})
     */
    private $children;

    /**
     * @ORM\Column(type="integer", length=4, nullable=false)
     */
    protected int $status = Category::UNPUBLISHED;

    public function setTitle($title)
    {
        $this->title = $title;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setParent(Category $parent = null)
    {
        $this->parent = $parent;
    }

    public function getParent()
    {
        return $this->parent;
    }

    /**
     * @param mixed $lvl
     */
    public function setLvl($lvl)
    {
        $this->lvl = $lvl;
    }

    /**
     * @return mixed
     */
    public function getLvl()
    {
        return $this->lvl;
    }

    /**
     * @param mixed $lft
     */
    public function setLft($lft)
    {
        $this->lft = $lft;
    }

    /**
     * @return mixed
     */
    public function getLft()
    {
        return $this->lft;
    }

    public function getRgt(): int
    {
        return $this->rgt;
    }

    public function getRoot(): int
    {
        return $this->root;
    }

    public function setRgt($rgt) {
        $this->rgt = $rgt;
    }

    public function setRoot($root) {
        $this->root = $root;
    }

    public function getStatus(): int
    {
        return $this->status;
    }

    public function setStatus($status) {
        $this->status = $status;
    }

    public function setChildren($children)
    {
        $this->children = $children;
    }

    public function getChildren()
    {
        return $this->children;
    }

    public function __toString()
    {
        $prefix = "";
        for ($i=2; $i<= $this->lvl; $i++){
            $prefix .= "---- ";
        }
        return $prefix . $this->title;
    }

    public function getLeveledTitle(): string
    {
        return (string)$this;
    }


    public function isParent(): bool
    {
        return $this->getLvl() == 1;
    }

    public static function getStatusList(): array
    {
        return array(
            Category::PUBLISHED => 'Publié',
            Category::UNPUBLISHED => 'Non publié',
            NULL => 'Non-défini'
        );
    }

    public static function getStatusName(): array
    {
        return array(
            Category::PUBLISHED => 'Publié',
            Category::UNPUBLISHED => 'Non publié'
        );
    }
}