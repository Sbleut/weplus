<?php

namespace App\Entity;

use App\Repository\EntreprisesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=EntreprisesRepository::class)
 */
class Entreprises
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
    private $nameEntreprise;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $lienEntreprise;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $logo;

    /**
     * @ORM\ManyToMany(targetEntity=Causes::class, inversedBy="entreprises")
     */
    private $causes;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $logoAlt;

    public function __construct()
    {
        $this->causes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNameEntreprise(): ?string
    {
        return $this->nameEntreprise;
    }

    public function setNameEntreprise(string $nameEntreprise): self
    {
        $this->nameEntreprise = $nameEntreprise;

        return $this;
    }

    public function getLienEntreprise(): ?string
    {
        return $this->lienEntreprise;
    }

    public function setLienEntreprise(string $lienEntreprise): self
    {
        $this->lienEntreprise = $lienEntreprise;

        return $this;
    }

    public function getLogo(): ?string
    {
        return $this->logo;
    }

    public function setLogo(string $logo): self
    {
        $this->logo = $logo;

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

    public function getLogoAlt(): ?string
    {
        return $this->logoAlt;
    }

    public function setLogoAlt(string $logoAlt): self
    {
        $this->logoAlt = $logoAlt;

        return $this;
    }
}
