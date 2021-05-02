<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * File
 *
 *
 * @ORM\Table(name="files")
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 */
class File
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=TbsModule::class, inversedBy="moduleImages",cascade={"persist"})
     */
    private $module;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $image_name;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $image_size;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getImageName(): ?string
    {
        return $this->image_name;
    }

    public function setImageName(string $image_name): self
    {
        $this->image_name = $image_name;

        return $this;
    }

    public function getImageSize(): ?string
    {
        return $this->image_size;
    }

    public function setImageSize(?string $image_size): self
    {
        $this->image_size = $image_size;

        return $this;
    }

    public function getModule(): ?TbsModule
    {
        return $this->module;
    }

    public function setModule(?TbsModule $module): self
    {
        $this->module = $module;

        return $this;
    }
}
