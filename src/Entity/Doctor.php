<?php

namespace App\Entity;

use App\Repository\DoctorRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=DoctorRepository::class)
 */
class Doctor extends User
{

    /**
     * @ORM\Column(type="string", length=30, nullable=true)
     */
    private $speciality;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $profile_overview;

    /**
     * @ORM\Column(type="boolean")
     */
    private $verified;


    public function __construct()
    {
        parent::__construct();
    }

    public function getSpeciality(): ?string
    {
        return $this->speciality;
    }

    public function setSpeciality(string $speciality): self
    {
        $this->speciality = $speciality;

        return $this;
    }

    public function getProfileOverview(): ?string
    {
        return $this->profile_overview;
    }

    public function setProfileOverview(?string $profile_overview): self
    {
        $this->profile_overview = $profile_overview;

        return $this;
    }

    public function getVerified(): ?bool
    {
        return $this->verified;
    }

    public function setVerified(bool $verified): self
    {
        $this->verified = $verified;

        return $this;
    }
}
