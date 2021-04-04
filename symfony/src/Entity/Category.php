<?php

namespace App\Entity;

use App\Repository\CategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CategoryRepository::class)
 */
class Category
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
     * @ORM\ManyToMany(targetEntity=Dvd::class, mappedBy="categories")
     */
    private $dvds;

    public function __construct()
    {
        $this->dvds = new ArrayCollection();
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
     * @return Collection|Dvd[]
     */
    public function getDvds(): Collection
    {
        return $this->dvds;
    }

    public function addDvd(Dvd $dvd): self
    {
        if (!$this->dvds->contains($dvd)) {
            $this->dvds[] = $dvd;
            $dvd->addCategory($this);
        }

        return $this;
    }

    public function removeDvd(Dvd $dvd): self
    {
        if ($this->dvds->removeElement($dvd)) {
            $dvd->removeCategory($this);
        }

        return $this;
    }
}
