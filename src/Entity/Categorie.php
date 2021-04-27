<?php

namespace App\Entity;

use App\Repository\CategorieRepository;
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
     * @ORM\Column(type="string", length=255)
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
}
