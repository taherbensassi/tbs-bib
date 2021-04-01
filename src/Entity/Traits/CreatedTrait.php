<?php

namespace App\Entity\Traits;

use Doctrine\ORM\Mapping as ORM;

/**
 */
trait CreatedTrait
{
    /**
     * @ORM\Column(type="datetime")
     */
    protected $created;


    /**
     * Gets triggered only on insert

     * @ORM\PrePersist
     */
    public function onPrePersist()
    {
        $this->created = new \DateTime("now");
    }

    /**
     * @return mixed
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * @param mixed $created
     */
    public function setCreated($created): void
    {
        $this->created = $created;
    }


}
