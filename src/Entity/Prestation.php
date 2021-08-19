<?php

namespace App\Entity;

use App\Repository\PrestationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PrestationRepository::class)
 */
class Prestation
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
    private $price;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $description;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $dateAvailable;

    /**
     * @ORM\Column(type="time", nullable=true)
     */
    private $hour;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $city;

    /**
     * @ORM\ManyToOne(targetEntity=Pro::class, inversedBy="prestation")
     */
    private $pro;

    /**
     * @ORM\ManyToMany(targetEntity=Dispo::class, inversedBy="prestations")
     */
    private $dispo;

    /**
     * @ORM\ManyToOne (targetEntity=SousService::class, inversedBy="prestation")
     */
    private $sousservice;

    public function __construct()
    {
        $this->dispo = new ArrayCollection();
        $this->sousservice = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPrice(): ?string
    {
        return $this->price;
    }

    public function setPrice(string $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getDateAvailable(): ?\DateTimeInterface
    {
        return $this->dateAvailable;
    }

    public function setDateAvailable(?\DateTimeInterface $dateAvailable): self
    {
        $this->dateAvailable = $dateAvailable;

        return $this;
    }

    public function getHour(): ?\DateTimeInterface
    {
        return $this->hour;
    }

    public function setHour(?\DateTimeInterface $hour): self
    {
        $this->hour = $hour;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(?string $city): self
    {
        $this->city = $city;

        return $this;
    }

    public function getPro(): ?Pro
    {
        return $this->pro;
    }

    public function setPro(?Pro $pro): self
    {
        $this->pro = $pro;

        return $this;
    }

    /**
     * @return Collection|Dispo[]
     */
    public function getDispo(): Collection
    {
        return $this->dispo;
    }

    public function addDispo(Dispo $dispo): self
    {
        if (!$this->dispo->contains($dispo)) {
            $this->dispo[] = $dispo;
        }

        return $this;
    }

    public function removeDispo(Dispo $dispo): self
    {
        $this->dispo->removeElement($dispo);

        return $this;
    }

    /**
     * @return Collection|SousService[]
     */
    public function getSousservice(): Collection
    {
        return $this->sousservice;
    }

    public function addSousservice(SousService $sousservice): self
    {
        if (!$this->sousservice->contains($sousservice)) {
            $this->sousservice[] = $sousservice;
            $sousservice->setPrestation($this);
        }

        return $this;
    }

    public function removeSousservice(SousService $sousservice): self
    {
        if ($this->sousservice->removeElement($sousservice)) {
            // set the owning side to null (unless already changed)
            if ($sousservice->getPrestation() === $this) {
                $sousservice->setPrestation(null);
            }
        }

        return $this;
    }
}
