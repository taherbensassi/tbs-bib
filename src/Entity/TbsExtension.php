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
use Symfony\Component\Validator\Constraints as Assert;


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
     * @ORM\Column(type="array")
     * @Assert\NotBlank
     */
    private $typo3Version;

    /**
     * @ORM\Column(type="string",nullable=true)
     * @Assert\Url
     */
    private $link;

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



    public function getAuthor(): ?User
    {
        return $this->author;
    }

    public function setAuthor(?User $author): self
    {
        $this->author = $author;

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
    public function getLink()
    {
        return $this->link;
    }

    /**
     * @param mixed $link
     */
    public function setLink($link): void
    {
        $this->link = $link;
    }




}
