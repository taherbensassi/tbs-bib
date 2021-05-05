<?php

namespace App\Entity;

use App\Entity\Traits\CreatedTrait;
use App\Entity\Traits\DeletedTrait;
use App\Entity\Traits\DescriptionTrait;
use App\Entity\Traits\HiddenTrait;
use App\Entity\Traits\IdTrait;
use App\Entity\Traits\UpdatedTrait;
use App\Repository\ContentElementsRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ContentElementsRepository::class)
 * @ORM\HasLifecycleCallbacks
 */
class ContentElements
{
    use IdTrait;
    use CreatedTrait;
    use UpdatedTrait;
    use DeletedTrait;
    use HiddenTrait;
    use DescriptionTrait;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $elementKey;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $elementTitle;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $shortTitle;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $icon;

    /**
     * @ORM\Column(type="text")
     */
    private $formData;

    /**
     * @ORM\ManyToOne(targetEntity=User::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getElementKey(): ?string
    {
        return $this->elementKey;
    }

    public function setElementKey(string $elementKey): self
    {
        $this->elementKey = $elementKey;

        return $this;
    }

    public function getElementTitle(): ?string
    {
        return $this->elementTitle;
    }

    public function setElementTitle(string $elementTitle): self
    {
        $this->elementTitle = $elementTitle;

        return $this;
    }

    public function getShortTitle(): ?string
    {
        return $this->shortTitle;
    }

    public function setShortTitle(string $shortTitle): self
    {
        $this->shortTitle = $shortTitle;

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
    public function getFormData()
    {
        return $this->formData;
    }

    /**
     * @param mixed $formData
     */
    public function setFormData($formData): void
    {
        $this->formData = $formData;
    }

    /**
     * @return mixed
     */
    public function getIcon()
    {
        return $this->icon;
    }

    /**
     * @param mixed $icon
     */
    public function setIcon($icon): void
    {
        $this->icon = $icon;
    }





}
