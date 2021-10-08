<?php

namespace App\Entity;

use App\Repository\CategorieRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CategorieRepository::class)
 */
class Categorie
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
    private $Title;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $sub_title;

    /**
     * @ORM\Column(type="text")
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $image_catego;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $image_catego_alt;

    /**
     * @ORM\OneToMany(targetEntity=Services::class, mappedBy="categorie")
     */
    private $serviceCat;

    /**
     * @ORM\Column(type="text")
     */
    private $google_description;

    public function __construct()
    {
        $this->serviceCat = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->Title;
    }

    public function setTitle(string $Title): self
    {
        $this->Title = $Title;

        return $this;
    }

    public function getSubTitle(): ?string
    {
        return $this->sub_title;
    }

    public function setSubTitle(string $sub_title): self
    {
        $this->sub_title = $sub_title;

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

    public function getImageCatego(): ?string
    {
        return $this->image_catego;
    }

    public function setImageCatego(string $image_catego): self
    {
        $this->image_catego = $image_catego;

        return $this;
    }

    public function getImageCategoAlt(): ?string
    {
        return $this->image_catego_alt;
    }

    public function setImageCategoAlt(string $image_catego_alt): self
    {
        $this->image_catego_alt = $image_catego_alt;

        return $this;
    }

    /**
     * @return Collection|Services[]
     */
    public function getServiceCat(): Collection
    {
        return $this->serviceCat;
    }

    public function addServiceCat(Services $serviceCat): self
    {
        if (!$this->serviceCat->contains($serviceCat)) {
            $this->serviceCat[] = $serviceCat;
            $serviceCat->setCategorie($this);
        }

        return $this;
    }

    public function removeServiceCat(Services $serviceCat): self
    {
        if ($this->serviceCat->removeElement($serviceCat)) {
            // set the owning side to null (unless already changed)
            if ($serviceCat->getCategorie() === $this) {
                $serviceCat->setCategorie(null);
            }
        }

        return $this;
    }

    public function getGoogleDescription(): ?string
    {
        return $this->google_description;
    }

    public function setGoogleDescription(string $google_description): self
    {
        $this->google_description = $google_description;

        return $this;
    }
}
