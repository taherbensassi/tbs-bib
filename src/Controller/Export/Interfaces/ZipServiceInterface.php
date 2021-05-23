<?php


namespace App\Controller\Export\Interfaces;


interface ZipServiceInterface
{

    /**
     * @return mixed
     */
    public function initialize();

    /**
     * @return mixed
     */
    public function createZip();


    /**
     * @return mixed
     */
    public function unsetTbsExtension();

    /**
     * @return mixed
     */
    public function downloadExtension();
}