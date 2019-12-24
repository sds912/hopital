<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ServiceRepository")
 */
class Service
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $label;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Medecin", mappedBy="service")
     */
    private $medecin;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Specialite", mappedBy="service")
     */
    private $specialites;

    public function __construct()
    {
        $this->medecin = new ArrayCollection();
        $this->specialites = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLabel(): ?string
    {
        return $this->label;
    }

    public function setLabel(string $label): self
    {
        $this->label = $label;

        return $this;
    }

    /**
     * @return Collection|Medecin[]
     */
    public function getMedecin(): Collection
    {
        return $this->medecin;
    }

    public function addMedecin(Medecin $medecin): self
    {
        if (!$this->medecin->contains($medecin)) {
            $this->medecin[] = $medecin;
            $medecin->setService($this);
        }

        return $this;
    }

    public function removeMedecin(Medecin $medecin): self
    {
        if ($this->medecin->contains($medecin)) {
            $this->medecin->removeElement($medecin);
            // set the owning side to null (unless already changed)
            if ($medecin->getService() === $this) {
                $medecin->setService(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Specialite[]
     */
    public function getSpecialites(): Collection
    {
        return $this->specialites;
    }

    public function addSpecialite(Specialite $specialite): self
    {
        if (!$this->specialites->contains($specialite)) {
            $this->specialites[] = $specialite;
            $specialite->setService($this);
        }

        return $this;
    }

    public function removeSpecialite(Specialite $specialite): self
    {
        if ($this->specialites->contains($specialite)) {
            $this->specialites->removeElement($specialite);
            // set the owning side to null (unless already changed)
            if ($specialite->getService() === $this) {
                $specialite->setService(null);
            }
        }

        return $this;
    }

    /**
     * Generates the magic method
     * 
     */
    public function __toString(){
        // to show the name of the Category in the select
        return $this->label;
        // to show the id of the Category in the select
        // return $this->id;
    }
}
