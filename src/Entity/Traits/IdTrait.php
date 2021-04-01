<?php

namespace App\Entity\Traits;

use Doctrine\ORM\Mapping as ORM;

/**
 */
trait IdTrait
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id = null;

    public function getId(): int
    {
        return $this->id;
    }

}
