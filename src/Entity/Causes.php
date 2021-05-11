<?php

namespace App\Entity;

use App\Repository\CausesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CausesRepository::class)
 */
class Causes
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
    private $nomCause;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $textCause;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $citation;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $lienVideo;

    /**
     * @ORM\ManyToMany(targetEntity=Associations::class, mappedBy="causes")
     */
    private $associations;

    /**
     * @ORM\ManyToMany(targetEntity=Entreprises::class, mappedBy="causes")
     */
    private $entreprises;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $imageCause;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $imageCause_alt;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $causeGoogleDescription;

    public function __construct()
    {
        $this->associations = new ArrayCollection();
        $this->entreprises = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomCause(): ?string
    {
        return $this->nomCause;
    }

    public function setNomCause(string $nomCause): self
    {
        $this->nomCause = $nomCause;

        return $this;
    }

    public function getTextCause(): ?string
    {
        return $this->textCause;
    }

    public function setTextCause(string $textCause): self
    {
        $this->textCause = $textCause;

        return $this;
    }

    public function getCitation(): ?string
    {
        return $this->citation;
    }

    public function setCitation(string $citation): self
    {
        $this->citation = $citation;

        return $this;
    }

    public function getLienVideo(): ?string
    {
        return $this->lienVideo;
    }

    public function setLienVideo(string $lienVideo): self
    {
        $this->lienVideo = $lienVideo;

        return $this;
    }

    /**
     * @return Collection|Associations[]
     */
    public function getAssociations(): Collection
    {
        return $this->associations;
    }

    public function addAssociation(Associations $association): self
    {
        if (!$this->associations->contains($association)) {
            $this->associations[] = $association;
            $association->addCause($this);
        }

        return $this;
    }

    public function removeAssociation(Associations $association): self
    {
        if ($this->associations->removeElement($association)) {
            $association->removeCause($this);
        }

        return $this;
    }

    /**
     * @return Collection|Entreprises[]
     */
    public function getEntreprises(): Collection
    {
        return $this->entreprises;
    }

    public function addEntreprise(Entreprises $entreprise): self
    {
        if (!$this->entreprises->contains($entreprise)) {
            $this->entreprises[] = $entreprise;
            $entreprise->addCause($this);
        }

        return $this;
    }

    public function removeEntreprise(Entreprises $entreprise): self
    {
        if ($this->entreprises->removeElement($entreprise)) {
            $entreprise->removeCause($this);
        }

        return $this;
    }

    public function getImageCause(): ?string
    {
        return $this->imageCause;
    }

    public function setImageCause(string $imageCause): self
    {
        $this->imageCause = $imageCause;

        return $this;
    }

    public function getImageCauseAlt(): ?string
    {
        return $this->imageCause_alt;
    }

    public function setImageCauseAlt(string $imageCause_alt): self
    {
        $this->imageCause_alt = $imageCause_alt;

        return $this;
    }

    public function getCauseGoogleDescription(): ?string
    {
        return $this->causeGoogleDescription;
    }

    public function setCauseGoogleDescription(string $causeGoogleDescription): self
    {
        $this->causeGoogleDescription = $causeGoogleDescription;

        return $this;
    }
}
