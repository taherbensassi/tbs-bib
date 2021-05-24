<?php


namespace App\Controller\Export\Interfaces;


interface ZipServiceInterface
{

    /**
     * this function will start with making a - mirror Copy -   for the our main extension tbs_content_elements_project
     * and put it in other folder with the name tbs_content_elements
     * @return mixed
     */
    public function initialize();

    /**
     * This function will create the extension zio from a folder
     * @return mixed
     */
    public function createZip();


    /**
     * This function will remove the folder created tbs_content_elements
     * @return mixed
     */
    public function unsetTbsExtension();

    /**
     * This function will donwlaod the extension
     * @return mixed
     */
    public function downloadExtension();
}