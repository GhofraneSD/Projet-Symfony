<?php

namespace App\Entity;

use App\Repository\FilableRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * @ORM\InheritanceType("JOINED")
 * @ORM\Entity(repositoryClass=FilableRepository::class)
 */
class Filable
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\OneToMany(targetEntity=File::class, mappedBy="filable")
     *
     */
    private $list_files;

    public function __construct()
    {
        $this->list_files = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection|File[]
     */
    public function getListFiles(): ?Collection
    {
        return $this->list_files;
    }

    public function addFile(File $file): self
    {
        $this->list_files[] = $file;
        $file->setFilable($this);
        
        return $this;
    }

    public function removeFile(File $file): self
    {
        if ($this->list_files->removeElement($file)) {
            // set the owning side to null (unless already changed)
            if ($file->getFilable() === $this) {
                $file->setFilable(null);
            }
        }

        return $this;
    }
}
