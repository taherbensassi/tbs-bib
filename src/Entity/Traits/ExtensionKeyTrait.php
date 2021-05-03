<?php


namespace App\Entity\Traits;


use Doctrine\ORM\Mapping as ORM;

trait ExtensionKeyTrait
{
    /**
     * @ORM\Column(type="string", length=255)
     */
    private $extensionKey;

    /**
     * @return mixed
     */
    public function getExtensionKey()
    {
        return $this->extensionKey;
    }

    /**
     * @param mixed $extensionKey
     */
    public function setExtensionKey($extensionKey): void
    {
        $this->extensionKey = $extensionKey;
    }


}