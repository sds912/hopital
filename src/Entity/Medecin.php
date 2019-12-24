<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\MedecinRepository")
 */
class Medecin
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=10, nullable=true)
     */
    private $matricule;

    /**
     * @ORM\Column(type="string", length=45)
     * @Assert\NotBlank(message = "le prenom ne peut pas etre vide")
     * 
     */
    private $prenom;

    /**
     * @ORM\Column(type="string", length=45)
     * @Assert\NotBlank(message="le nom ne peut pas etre vide")
     */
    private $nom;
    
    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $birthday;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="l'email ne peut pas etre vide")
     * @Assert\Email( message = "invalid email")
     * 
     */
    protected $email;

    /**
     * @ORM\Column(type="string", length=14)
     * @Assert\NotBlank(message="le telehone ne peut pas etre vide")
     * @Assert\Regex(pattern="/^\+221\s(77)?[0-9]*$/", message="Invalid phone number")
     * @Assert\Length(min=14, max=14, minMessage="invalid", maxMessage="invalid")
     */

    private $telephone;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Service", inversedBy="medecin")
     * @Assert\NotBlank(message="selectionnez un service")
     * @Assert\Valid()
     */
    private $service;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Specialite", mappedBy="medecin")
     * @Assert\NotBlank(message="selctionnez au moins une specialite")
     * @Assert\Valid()
     */
    private $specialites;

    

    public function __construct()
    {
        $this->specialites = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMatricule(): ?string
    {
        return $this->matricule;
    }

    public function setMatricule(string $matricule): self
    {
        $this->matricule = $matricule;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): self
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getTelephone(): ?string
    {
        return $this->telephone;
    }

    public function setTelephone(string $telephone): self
    {
        $this->telephone = $telephone;

        return $this;
    }

    public function getService(): ?Service
    {
        return $this->service;
    }

    public function setService(?Service $service): self
    {
        $this->service = $service;

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
            $specialite->addMedecin($this);
        }

        return $this;
    }

    public function removeSpecialite(Specialite $specialite): self
    {
        if ($this->specialites->contains($specialite)) {
            $this->specialites->removeElement($specialite);
            $specialite->removeMedecin($this);
        }

        return $this;
    }

    public function getBirthday(): ?\DateTimeInterface
    {
        return $this->birthday;
    }

    public function setBirthday(\DateTimeInterface $birthday): self
    {
        $this->birthday = $birthday;

        return $this;
    }
}
