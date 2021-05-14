<?php

declare(strict_types=1);

namespace App\Entity;

trait Timestapable
{
    /**
     * @ORM\Column(type="datetime")
     */
    private \DateTimeImmutable $createdAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private \DateTime $updatedAt;

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }
}
