<?php

namespace App\Entity;

use App\Repository\PageRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity(repositoryClass=PageRepository::class)
 */
class Page
{
    const TYPE_PAGE_CANDIDAT = 1;
    const TYPE_PAGE_RECRUTEUR = 2;
    const TYPE_PAGE_AUTRE = 3;

    use ResourceId;
    use Timestapable;
    /**
     * @ORM\Column(type="string", length=255)
     */
    private string $title;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private string $content;

    /**
     * @ORM\Column(type="text")
     */
    private string $style;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private string $type;

    /**
     * @Gedmo\Slug(fields={"title"})
     * @ORM\Column(type="string", length=255)
     */
    private ?string $slug;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $meta_title;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $meta_description;

    /**
     * @ORM\OneToMany(targetEntity=File::class, mappedBy="pages", orphanRemoval=true, cascade={"persist"})
     */
    private Collection $files;

    public function __construct()
    {
        $this->createdAt = new \DateTimeImmutable('now');
        $this->files = new ArrayCollection();
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

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(?string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getStyle(): ?string
    {
        return $this->style;
    }

    public function setStyle(?string $style): self
    {
        $this->style = $style;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(?string $type): self
    {
        $this->type = $type;

        return $this;
    }
    public function setSlug(?string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function getMetaTitle(): ?string
    {
        return $this->meta_title;
    }

    public function setMetaTitle(?string $meta_title): self
    {
        $this->meta_title = $meta_title;

        return $this;
    }

    public function getMetaDescription(): ?string
    {
        return $this->meta_description;
    }

    public function setMetaDescription(?string $meta_description): self
    {
        $this->meta_description = $meta_description;

        return $this;
    }


    /**
     * @return int[]
     */
    public static function getTypeList(): array
    {
        return array(
            'Page Candidat' => Page::TYPE_PAGE_CANDIDAT,
            'Page Recruteur' => Page::TYPE_PAGE_RECRUTEUR,
            'Page global' => Page::TYPE_PAGE_AUTRE,
        );
    }

    /**
     * @return Collection
     */
    public function getFiles(): Collection
    {
        return $this->files;
    }

    public function addFile(File $file): self
    {
        if (!$this->files->contains($file)) {
            $this->files[] = $file;
            $file->setPages($this);
        }

        return $this;
    }

    public function removeFile(File $file): self
    {
        if ($this->files->removeElement($file)) {
            // set the owning side to null (unless already changed)
            if ($file->getPages() === $this) {
                $file->setPages(null);
            }
        }

        return $this;
    }
    /**
     * @return string
     */
    public function getUrlLastFile(): string
    {
        $url = "";
        $files = array();
        foreach ($this->getFiles() as $file){
            $files[] = $file->getName();
        }

        $file = end($files);
        $url = "uploads/" . $file;
        return $url ;
    }

    /**
     * @return string
     */
    public function getNameLastFile(): string
    {
        $files = array();
        foreach ($this->getFiles() as $file){
            $files[] = $file->getNameFile();
        }

        $file = end($files);
        $name = $file;
        return $name ;
    }

    public function __toString(): string
    {
        return $this->title;
    }
}
