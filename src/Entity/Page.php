<?php

namespace App\Entity;

use App\Repository\PageRepository;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Vich\UploaderBundle\Mapping\Annotation as Vich;


/**
 * @ORM\Entity(repositoryClass=PageRepository::class)
 * @Vich\Uploadable
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
     * @var string|null
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $filename;

    /**
     * @var File
     * @Vich\UploadableField(mapping="pages_images", fileNameProperty="filename")
     */
    private File $imageFile;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $meta_title;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $meta_description;

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

    public function getFilename(): ?string
    {
        return $this->filename;
    }

    public function setFilename(?string $filename): void
    {
        $this->filename = $filename;
    }

    public function getImageFile(): File
    {
        return $this->imageFile;
    }

    public function setImageFile(File $filename = null)
    {
        $this->imageFile = $filename;

        if ($filename) {
            $this->updatedAt = new \DateTime('now');
        }
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
}
