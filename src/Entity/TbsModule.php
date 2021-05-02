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
use Symfony\Component\Validator\Constraints as Assert;

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
    use LinkTrait;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank
     */
    private $title;

    /**
     * @ORM\Column(type="string")
     */
    private $previewImageFileName;

    /**
     * @ORM\Column(type="text",nullable=true)
     *
     */
    private $description;

    /**
     * @ORM\OneToMany(targetEntity=File::class, mappedBy="module",cascade={"persist", "remove"})
     */
    private $moduleImages;

    /**
     * @ORM\Column(type="string")
     */
    private $typo3Version;

    /**
     * TbsModule constructor.
     */
    public function __construct()
    {
        $this->moduleImages = new ArrayCollection();
    }

    /**
     * @return string|null
     */
    public function getTitle(): ?string
    {
        return $this->title;
    }

    /**
     * @param string $title
     * @return $this
     */
    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }


    /**
     * @return string|null
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * @param string $description
     * @return $this
     */
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

    /**
     * @param File $moduleImage
     * @return $this
     */
    public function addModuleImage(File $moduleImage): self
    {
        if (!$this->moduleImages->contains($moduleImage)) {
            $this->moduleImages[] = $moduleImage;
            $moduleImage->setModule($this);
        }

        return $this;
    }

    /**
     * @param File $moduleImage
     * @return $this
     */
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

    /**
     * @return mixed
     */
    public function getTypo3Version()
    {
        return $this->typo3Version;
    }

    /**
     * @param mixed $typo3Version
     */
    public function setTypo3Version($typo3Version): void
    {
        $this->typo3Version = $typo3Version;
    }



}
