<?php

namespace App\Entity\Traits;

use Doctrine\ORM\Mapping as ORM;

/**
 */
trait UpdatedTrait
{
    /**
     * @ORM\Column(type="datetime",nullable=true)
     */
    protected $updated = null;

    /**
     * @return mixed
     */
    public function getUpdated()
    {
        return $this->updated;
    }

    /**
     * @param mixed $updated
     */
    public function setUpdated($updated): void
    {
        $this->updated = $updated;
    }


    /**
     * Gets triggered every time on update

     * @ORM\PreUpdate
     */
    public function onPreUpdate()
    {
        $this->updated = new \DateTime("now");
    }


}
