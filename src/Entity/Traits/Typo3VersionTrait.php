<?php


namespace App\Entity\Traits;


use Doctrine\ORM\Mapping as ORM;

trait Typo3VersionTrait
{

    /**
     * @ORM\Column(type="integer")
     */
    private $typo3Version;

    /**
     * @return mixed
     */
    public function getTypo3Version()
    {
        return $this->typo3Version;
    }

    /**
     * @param mixed $typo3Version
     */
    public function setTypo3Version($typo3Version): void
    {
        $this->typo3Version = $typo3Version;
    }



}