<?php


namespace App\Entity\Traits;


use Doctrine\ORM\Mapping as ORM;

trait LinkTrait
{
    /**
     * @ORM\Column(type="string",nullable=true)
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