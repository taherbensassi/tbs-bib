<?php


namespace App\Entity\Traits;


use Doctrine\ORM\Mapping as ORM;

trait ExtensionNameTrait
{
    /**
     * @ORM\Column(type="string", length=255)
     */
    private $extensionName;

    /**
     * @return mixed
     */
    public function getExtensionName()
    {
        return $this->extensionName;
    }

    /**
     * @param mixed $extensionName
     */
    public function setExtensionName($extensionName): void
    {
        $this->extensionName = $extensionName;
    }


}