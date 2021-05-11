<?php

namespace App\Entity;

use App\Repository\AssociationsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=AssociationsRepository::class)
 */
class Associations
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $nameAsso;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $lienAsso;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $resoAsso;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $textAsso;

    /**
     * @ORM\ManyToMany(targetEntity=Causes::class, inversedBy="associations")
     */
    private $causes;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $assoImage;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $assoImageAlt;

    public function __construct()
    {
        $this->causes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNameAsso(): ?string
    {
        return $this->nameAsso;
    }

    public function setNameAsso(string $nameAsso): self
    {
        $this->nameAsso = $nameAsso;

        return $this;
    }

    public function getLienAsso(): ?string
    {
        return $this->lienAsso;
    }

    public function setLienAsso(string $lienAsso): self
    {
        $this->lienAsso = $lienAsso;

        return $this;
    }

    public function getResoAsso(): ?string
    {
        return $this->resoAsso;
    }

    public function setResoAsso(string $resoAsso): self
    {
        $this->resoAsso = $resoAsso;

        return $this;
    }

    public function getTextAsso(): ?string
    {
        return $this->textAsso;
    }

    public function setTextAsso(string $textAsso): self
    {
        $this->textAsso = $textAsso;

        return $this;
    }

    /**
     * @return Collection|Causes[]
     */
    public function getCauses(): Collection
    {
        return $this->causes;
    }

    public function addCause(Causes $cause): self
    {
        if (!$this->causes->contains($cause)) {
            $this->causes[] = $cause;
        }

        return $this;
    }

    public function removeCause(Causes $cause): self
    {
        $this->causes->removeElement($cause);

        return $this;
    }

    public function getAssoImage(): ?string
    {
        return $this->assoImage;
    }

    public function setAssoImage(string $assoImage): self
    {
        $this->assoImage = $assoImage;

        return $this;
    }

    public function getAssoImageAlt(): ?string
    {
        return $this->assoImageAlt;
    }

    public function setAssoImageAlt(string $assoImageAlt): self
    {
        $this->assoImageAlt = $assoImageAlt;

        return $this;
    }
}
