<?php

namespace App\Entity;

use App\Entity\Traits\CreatedTrait;
use App\Entity\Traits\IdTrait;
use App\Entity\Traits\UpdatedTrait;
use App\Repository\SitePackageRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=SitePackageRepository::class)
 * @ORM\HasLifecycleCallbacks
 */
class SitePackage
{
    use IdTrait;
    use CreatedTrait;
    use UpdatedTrait;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $projectName;

    /**
     * @ORM\Column(type="integer")
     */
    private $typo3Version;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $basePackage;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $title;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $repositoryUrl;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $author;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $authorEmail;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $authorCompany;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $authorHomePage;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="sitePackage")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;



    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTypo3Version(): ?int
    {
        return $this->typo3Version;
    }

    public function setTypo3Version(int $typo3Version): self
    {
        $this->typo3Version = $typo3Version;

        return $this;
    }

    public function getBasePackage(): ?string
    {
        return $this->basePackage;
    }

    public function setBasePackage(string $basePackage): self
    {
        $this->basePackage = $basePackage;

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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getRepositoryUrl(): ?string
    {
        return $this->repositoryUrl;
    }

    public function setRepositoryUrl(string $repositoryUrl): self
    {
        $this->repositoryUrl = $repositoryUrl;

        return $this;
    }

    public function getAuthor(): ?string
    {
        return $this->author;
    }

    public function setAuthor(string $author): self
    {
        $this->author = $author;

        return $this;
    }

    public function getAuthorEmail(): ?string
    {
        return $this->authorEmail;
    }

    public function setAuthorEmail(string $authorEmail): self
    {
        $this->authorEmail = $authorEmail;

        return $this;
    }

    public function getAuthorCompany(): ?string
    {
        return $this->authorCompany;
    }

    public function setAuthorCompany(string $authorCompany): self
    {
        $this->authorCompany = $authorCompany;

        return $this;
    }

    public function getAuthorHomePage(): ?string
    {
        return $this->authorHomePage;
    }

    public function setAuthorHomePage(string $authorHomePage): self
    {
        $this->authorHomePage = $authorHomePage;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param mixed $user
     */
    public function setUser($user): void
    {
        $this->user = $user;
    }

    /**
     * @return mixed
     */
    public function getProjectName()
    {
        return $this->projectName;
    }

    /**
     * @param mixed $projectName
     */
    public function setProjectName($projectName): void
    {
        $this->projectName = $projectName;
    }


}
