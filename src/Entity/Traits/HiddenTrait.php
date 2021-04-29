<?php


namespace App\Entity\Traits;


use Doctrine\ORM\Mapping as ORM;

trait HiddenTrait
{
    /**
     * @ORM\Column(type="boolean")
     */
    private $hidden = false;

    /**
     * @return bool
     */
    public function isHidden(): bool
    {
        return $this->hidden;
    }

    /**
     * @param bool $hidden
     */
    public function setHidden(bool $hidden): void
    {
        $this->hidden = $hidden;
    }

}