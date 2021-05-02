<?php


namespace App\Entity\Traits;


use Doctrine\ORM\Mapping as ORM;

trait LinkTrait
{
    /**
     * @ORM\Column(type="string")
     */
    private $link;

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