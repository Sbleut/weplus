<?php

namespace App\Entity;

use App\Repository\MatosRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=MatosRepository::class)
 */
class Matos
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
    private $name_matos;

    /**
     * @ORM\ManyToOne(targetEntity=MatosCatego::class, inversedBy="matos")
     * @ORM\JoinColumn(nullable=false)
     */
    private $matos_catego;

    /**
     * @ORM\Column(type="integer")
     */
    private $stock;

    /**
     * @ORM\Column(type="integer")
     */
    private $prix_ht;

    /**
     * @ORM\Column(type="integer")
     */
    private $caution;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $matos_image;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $matos_image_alt;

    /**
     * @ORM\Column(type="array", nullable=true)
     */
    private $accessoires = [];

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $detail;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNameMatos(): ?string
    {
        return $this->name_matos;
    }

    public function setNameMatos(string $name_matos): self
    {
        $this->name_matos = $name_matos; 

        return $this;
    }

    public function getMatosCatego(): ?MatosCatego
    {
        return $this->matos_catego;
    }

    public function setMatosCatego(?MatosCatego $matos_catego): self
    {
        $this->matos_catego = $matos_catego;

        return $this;
    }

    public function getStock(): ?int
    {
        return $this->stock;
    }

    public function setStock(int $stock): self
    {
        $this->stock = $stock;

        return $this;
    }

    public function getPrixHt(): ?int
    {
        return $this->prix_ht;
    }

    public function setPrixHt(int $prix_ht): self
    {
        $this->prix_ht = $prix_ht;

        return $this;
    }

    public function getCaution(): ?int
    {
        return $this->caution;
    }

    public function setCaution(int $caution): self
    {
        $this->caution = $caution;

        return $this;
    }

    public function getMatosImage(): ?string
    {
        return $this->matos_image;
    }

    public function setMatosImage(string $matos_image): self
    {
        $this->matos_image = $matos_image;

        return $this;
    }

    public function getMatosImageAlt(): ?string
    {
        return $this->matos_image_alt;
    }

    public function setMatosImageAlt(string $matos_image_alt): self
    {
        $this->matos_image_alt = $matos_image_alt;

        return $this;
    }

    public function getAccessoires(): ?array
    {
        return $this->accessoires;
    }

    public function setAccessoires(?array $accessoires): self
    {
        $this->accessoires = $accessoires;

        return $this;
    }

    public function getDetail(): ?string
    {
        return $this->detail;
    }

    public function setDetail(?string $detail): self
    {
        $this->detail = $detail;

        return $this;
    }
}
