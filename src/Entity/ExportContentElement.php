<?php

namespace App\Entity;

use App\Entity\Traits\CreatedTrait;
use App\Entity\Traits\DeletedTrait;
use App\Entity\Traits\HiddenTrait;
use App\Entity\Traits\IdTrait;
use App\Entity\Traits\StatusTrait;
use App\Entity\Traits\UpdatedTrait;
use App\Repository\ExportContentElementRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ExportContentElementRepository::class)
 * @ORM\HasLifecycleCallbacks
 */
class ExportContentElement
{
    use IdTrait;
    use CreatedTrait;
    use UpdatedTrait;
    use DeletedTrait;
    use HiddenTrait;
    use StatusTrait;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $extensionName;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $vendorName;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $icon;

    /**
     * @ORM\Column(type="text",nullable=false)
     */
    private $tbsModule;

    /**
     * @ORM\Column(type="text",nullable=false)
     */
    private $customModule;



    /**
     * @ORM\ManyToOne(targetEntity=User::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    public function getExtensionName(): ?string
    {
        return $this->extensionName;
    }

    public function setExtensionName(string $extensionName): self
    {
        $this->extensionName = $extensionName;

        return $this;
    }

    public function getVendorName(): ?string
    {
        return $this->vendorName;
    }

    public function setVendorName(string $vendorName): self
    {
        $this->vendorName = $vendorName;

        return $this;
    }

    public function getIcon(): ?string
    {
        return $this->icon;
    }

    public function setIcon(string $icon): self
    {
        $this->icon = $icon;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getTbsModule()
    {
        return $this->tbsModule;
    }

    /**
     * @param mixed $tbsModule
     */
    public function setTbsModule($tbsModule): void
    {
        $this->tbsModule = $tbsModule;
    }

    /**
     * @return mixed
     */
    public function getCustomModule()
    {
        return $this->customModule;
    }

    /**
     * @param mixed $customModule
     */
    public function setCustomModule($customModule): void
    {
        $this->customModule = $customModule;
    }
}
