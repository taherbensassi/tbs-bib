<?php

namespace App\Entity;

use App\Entity\Traits\CreatedTrait;
use App\Entity\Traits\DeletedTrait;
use App\Entity\Traits\HiddenTrait;
use App\Entity\Traits\IdTrait;
use App\Entity\Traits\LinkTrait;
use App\Entity\Traits\Typo3VersionTrait;
use App\Entity\Traits\UpdatedTrait;
use App\Repository\TbsModuleRepository;
use App\Service\FileUploader;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TbsModuleRepository::class)
 * @ORM\HasLifecycleCallbacks
 */
class TbsModule
{

    use IdTrait;
    use CreatedTrait;
    use UpdatedTrait;
    use DeletedTrait;
    use HiddenTrait;
    use Typo3VersionTrait;
    use LinkTrait;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $title;

    /**
     * @ORM\Column(type="string")
     */
    private $previewImageFileName;

    /**
     * @ORM\Column(type="text")
     */
    private $description;

    /**
     * @ORM\OneToMany(targetEntity=File::class, mappedBy="module",cascade={"persist", "remove"})
     */
    private $moduleImages;

    public function __construct()
    {
        $this->moduleImages = new ArrayCollection();
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


    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getPreviewImageFileName()
    {
        return $this->previewImageFileName;
    }

    /**
     * @param mixed $previewImageFileName
     */
    public function setPreviewImageFileName($previewImageFileName): void
    {
        $this->previewImageFileName = $previewImageFileName;
    }

    /**
     * @return Collection|File[]
     */
    public function getModuleImages(): Collection
    {
        return $this->moduleImages;
    }

    public function addModuleImage(File $moduleImage): self
    {
        if (!$this->moduleImages->contains($moduleImage)) {
            $this->moduleImages[] = $moduleImage;
            $moduleImage->setModule($this);
        }

        return $this;
    }

    public function removeModuleImage(File $moduleImage): self
    {
        if ($this->moduleImages->removeElement($moduleImage)) {
            // set the owning side to null (unless already changed)
            if ($moduleImage->getModule() === $this) {
                $moduleImage->setModule(null);
            }
        }

        return $this;
    }
}
