<?php

namespace App\Entity;

use App\Repository\ServiceRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ServiceRepository::class)
 */
class Service
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
    private $nom;

    /**
     * @ORM\OneToMany(targetEntity=SousService::class, mappedBy="service")
     */
    private $sousService;

    public function __construct()
    {
        $this->sousService = new ArrayCollection();
    }









    public function getId(): ?int
    {
        return $this->id;
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

    /**
     * @return Collection|SousService[]
     */
    public function getSousService(): Collection
    {
        return $this->sousService;
    }

    public function addSousService(SousService $sousService): self
    {
        if (!$this->sousService->contains($sousService)) {
            $this->sousService[] = $sousService;
            $sousService->setService($this);
        }

        return $this;
    }

    public function removeSousService(SousService $sousService): self
    {
        if ($this->sousService->removeElement($sousService)) {
            // set the owning side to null (unless already changed)
            if ($sousService->getService() === $this) {
                $sousService->setService(null);
            }
        }

        return $this;
    }





}
