<?php

namespace App\Entity;

use App\Repository\MatosCategoRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=MatosCategoRepository::class)
 */
class MatosCatego
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
    private $name;

    /**
     * @ORM\OneToMany(targetEntity=Matos::class, mappedBy="matos_catego")
     */
    private $matos;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $matos_catego_image;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $matos_catego_image_alt;

    public function __construct()
    {
        $this->matos = new ArrayCollection();
    }

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

    /**
     * @return Collection|Matos[]
     */
    public function getMatos(): Collection
    {
        return $this->matos;
    }

    public function addMato(Matos $mato): self
    {
        if (!$this->matos->contains($mato)) {
            $this->matos[] = $mato;
            $mato->setMatosCatego($this);
        }

        return $this;
    }

    public function removeMato(Matos $mato): self
    {
        if ($this->matos->removeElement($mato)) {
            // set the owning side to null (unless already changed)
            if ($mato->getMatosCatego() === $this) {
                $mato->setMatosCatego(null);
            }
        }

        return $this;
    }

    public function getMatosCategoImage(): ?string
    {
        return $this->matos_catego_image;
    }

    public function setMatosCategoImage(string $matos_catego_image): self
    {
        $this->matos_catego_image = $matos_catego_image;

        return $this;
    }

    public function getMatosCategoImageAlt(): ?string
    {
        return $this->matos_catego_image_alt;
    }

    public function setMatosCategoImageAlt(string $matos_catego_image_alt): self
    {
        $this->matos_catego_image_alt = $matos_catego_image_alt;

        return $this;
    }
}
