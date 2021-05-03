<?php

namespace App\Entity;

use App\Repository\Typo3VersionRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=Typo3VersionRepository::class)
 */
class Typo3Version
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
    private $version;

    /**
     * @ORM\ManyToOne(targetEntity=TbsExtension::class, inversedBy="typo3Version",cascade={"persist"})
     */
    private $tbsExtension;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $title;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getVersion(): ?string
    {
        return $this->version;
    }

    public function setVersion(string $version): self
    {
        $this->version = $version;

        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getTbsExtension(): ?TbsExtension
    {
        return $this->tbsExtension;
    }

    public function setTbsExtension(?TbsExtension $tbsExtension): self
    {
        $this->tbsExtension = $tbsExtension;

        return $this;
    }
}
