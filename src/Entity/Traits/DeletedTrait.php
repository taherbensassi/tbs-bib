<?php

namespace App\Entity\Traits;


use Doctrine\ORM\Mapping as ORM;

trait DeletedTrait
{

    /**
     * @ORM\Column(type="boolean")
     */
    private $deleted = false;

    /**
     * @return bool
     */
    public function isDeleted(): bool
    {
        return $this->deleted;
    }

    /**
     * @param bool $deleted
     */
    public function setDeleted(bool $deleted): void
    {
        $this->deleted = $deleted;
    }


}