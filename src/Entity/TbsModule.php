<?php

namespace App\Entity;

use App\Entity\Traits\CreatedTrait;
use App\Entity\Traits\DeletedTrait;
use App\Entity\Traits\DescriptionTrait;
use App\Entity\Traits\HiddenTrait;
use App\Entity\Traits\IdTrait;
use App\Entity\Traits\LinkTrait;
use App\Entity\Traits\StatusTrait;
use App\Entity\Traits\Typo3VersionTrait;
use App\Entity\Traits\UpdatedTrait;
use App\Repository\TbsModuleRepository;
use App\Service\FileUploader;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=TbsModuleRepository::class)
 * @ORM\HasLifecycleCallbacks
 * @UniqueEntity(fields={"title"})
 * @UniqueEntity(fields={"moduleKey"})
 *
 */
class TbsModule
{

    use IdTrait;
    use CreatedTrait;
    use UpdatedTrait;
    use DeletedTrait;
    use HiddenTrait;
    use LinkTrait;
    use DescriptionTrait;
    use StatusTrait;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank
     */
    private $title;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $moduleKey;

    /**
     * @ORM\OneToMany(targetEntity=File::class, mappedBy="module",cascade={"persist", "remove"})
     */
    private $moduleImages;

    /**
     * @ORM\Column(type="array")
     * @Assert\NotBlank
     */
    private $typo3Version;

    /**
     * @ORM\ManyToOne(targetEntity=User::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $author;

    /**
     * @ORM\Column(type="text")
     */
    private $tsConfigCode;

    /**
     * @ORM\Column(type="text")
     */
    private $typoScriptCode;

    /**
     * @ORM\Column(type="text")
     */
    private $ttContentCode;

    /**
     * @ORM\Column(type="text")
     */
    private $sqlOverrideCode;

    /**
     * @ORM\Column(type="text")
     */
    private $sqlNewTableCode;

    /**
     * @ORM\Column(type="text")
     */
    private $backendPreviewCode;

    /**
     * @ORM\Column(type="text")
     */
    private $htmlCode;

    /**
     * @ORM\Column(type="text")
     */
    private $localLangCode;

    /**
     * @ORM\Column(type="text")
     */
    private $deLangeCode;


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



    /**
     * @return mixed
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * @param mixed $author
     */
    public function setAuthor($author): void
    {
        $this->author = $author;
    }

    /**
     * @return mixed
     */
    public function getTsConfigCode()
    {
        return $this->tsConfigCode;
    }

    /**
     * @param mixed $tsConfigCode
     */
    public function setTsConfigCode($tsConfigCode): void
    {
        $this->tsConfigCode = $tsConfigCode;
    }

    /**
     * @return mixed
     */
    public function getTypoScriptCode()
    {
        return $this->typoScriptCode;
    }

    /**
     * @param mixed $typoScriptCode
     */
    public function setTypoScriptCode($typoScriptCode): void
    {
        $this->typoScriptCode = $typoScriptCode;
    }

    /**
     * @return mixed
     */
    public function getTtContentCode()
    {
        return $this->ttContentCode;
    }

    /**
     * @param mixed $ttContentCode
     */
    public function setTtContentCode($ttContentCode): void
    {
        $this->ttContentCode = $ttContentCode;
    }

    /**
     * @return mixed
     */
    public function getSqlOverrideCode()
    {
        return $this->sqlOverrideCode;
    }

    /**
     * @param mixed $sqlOverrideCode
     */
    public function setSqlOverrideCode($sqlOverrideCode): void
    {
        $this->sqlOverrideCode = $sqlOverrideCode;
    }

    /**
     * @return mixed
     */
    public function getSqlNewTableCode()
    {
        return $this->sqlNewTableCode;
    }

    /**
     * @param mixed $sqlNewTableCode
     */
    public function setSqlNewTableCode($sqlNewTableCode): void
    {
        $this->sqlNewTableCode = $sqlNewTableCode;
    }

    /**
     * @return mixed
     */
    public function getBackendPreviewCode()
    {
        return $this->backendPreviewCode;
    }

    /**
     * @param mixed $backendPreviewCode
     */
    public function setBackendPreviewCode($backendPreviewCode): void
    {
        $this->backendPreviewCode = $backendPreviewCode;
    }


    /**
     * @return mixed
     */
    public function getHtmlCode()
    {
        return $this->htmlCode;
    }

    /**
     * @param mixed $htmlCode
     */
    public function setHtmlCode($htmlCode): void
    {
        $this->htmlCode = $htmlCode;
    }

    /**
     * @return mixed
     */
    public function getModuleKey()
    {
        return $this->moduleKey;
    }

    /**
     * @param mixed $moduleKey
     */
    public function setModuleKey($moduleKey): void
    {
        $this->moduleKey = $moduleKey;
    }

    /**
     * @return mixed
     */
    public function getLocalLangCode()
    {
        return $this->localLangCode;
    }

    /**
     * @param mixed $localLangCode
     */
    public function setLocalLangCode($localLangCode): void
    {
        $this->localLangCode = $localLangCode;
    }

    /**
     * @return mixed
     */
    public function getDeLangeCode()
    {
        return $this->deLangeCode;
    }

    /**
     * @param mixed $deLangeCode
     */
    public function setDeLangeCode($deLangeCode): void
    {
        $this->deLangeCode = $deLangeCode;
    }




}
