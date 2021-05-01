<?php

namespace App\Entity;

use App\Entity\Traits\CreatedTrait;
use App\Entity\Traits\DeletedTrait;
use App\Entity\Traits\HiddenTrait;
use App\Entity\Traits\IdTrait;
use App\Entity\Traits\UpdatedTrait;
use App\Repository\TbsModuleRepository;
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

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $title;

    /**
     * @ORM\Column(type="string")
     */
    private $image;

    /**
     * @ORM\Column(type="text")
     */
    private $description;


    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(string $image): self
    {
        $this->image = $image;

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
}
