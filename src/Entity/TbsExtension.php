<?php

namespace App\Entity;

use App\Entity\Traits\CreatedTrait;
use App\Entity\Traits\DeletedTrait;
use App\Entity\Traits\DescriptionTrait;
use App\Entity\Traits\ExtensionKeyTrait;
use App\Entity\Traits\HiddenTrait;
use App\Entity\Traits\IdTrait;
use App\Entity\Traits\LinkTrait;
use App\Entity\Traits\Typo3VersionTrait;
use App\Entity\Traits\UpdatedTrait;
use App\Repository\TbsExtensionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TbsExtensionRepository::class)
 * @ORM\HasLifecycleCallbacks
 */
class TbsExtension
{
    use IdTrait;
    use CreatedTrait;
    use UpdatedTrait;
    use DeletedTrait;
    use HiddenTrait;
    use DescriptionTrait;
    use LinkTrait;
    use ExtensionKeyTrait;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $title;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $extensionZip;

    /**
     * @ORM\OneToMany(targetEntity=Typo3Version::class, mappedBy="tbsExtension",cascade={"persist", "remove"})
     */
    private $typo3Version;

    /**
     * @ORM\ManyToOne(targetEntity=User::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $author;

    public function __construct()
    {
        $this->typo3Version = new ArrayCollection();
    }


    public function getId(): ?int
    {
        return $this->id;
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

    /**
     * @return mixed
     */
    public function getExtensionZip()
    {
        return $this->extensionZip;
    }

    /**
     * @param mixed $extensionZip
     */
    public function setExtensionZip($extensionZip): void
    {
        $this->extensionZip = $extensionZip;
    }

    /**
     * @return Collection|Typo3Version[]
     */
    public function getTypo3Version(): Collection
    {
        return $this->typo3Version;
    }

    public function addTypo3Version(Typo3Version $typo3Version): self
    {
        if (!$this->typo3Version->contains($typo3Version)) {
            $this->typo3Version[] = $typo3Version;
            $typo3Version->setTbsExtension($this);
        }

        return $this;
    }

    public function removeTypo3Version(Typo3Version $typo3Version): self
    {
        if ($this->typo3Version->removeElement($typo3Version)) {
            // set the owning side to null (unless already changed)
            if ($typo3Version->getTbsExtension() === $this) {
                $typo3Version->setTbsExtension(null);
            }
        }

        return $this;
    }

    public function getAuthor(): ?User
    {
        return $this->author;
    }

    public function setAuthor(?User $author): self
    {
        $this->author = $author;

        return $this;
    }


}
