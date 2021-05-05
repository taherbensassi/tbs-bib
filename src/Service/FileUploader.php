<?php


// src/Service/FileUploader.php
namespace App\Service;

use App\Entity\File;
use Psr\Container\ContainerInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\VarDumper\VarDumper;

class FileUploader
{
    /**
     * @var
     */
    private $targetDirectory;
    /**
     * @var SluggerInterface
     */
    private $slugger;

    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * FileUploader constructor.
     * @param $targetDirectory
     * @param SluggerInterface $slugger
     * @param ContainerInterface $container
     */
    public function __construct($targetDirectory, SluggerInterface $slugger, ContainerInterface $container)
    {
        $this->targetDirectory = $targetDirectory;
        $this->slugger = $slugger;
        $this->container = $container;
    }


    /**
     * @param UploadedFile $file
     * @return string|null
     */
    public function upload(UploadedFile $file,bool $extensionDirectory = false)
    {
        $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $safeFilename = $this->slugger->slug($originalFilename);
        $fileName = $safeFilename.'-'.uniqid().'.'.$file->guessExtension();
        try {
            if(true === $extensionDirectory){
                $extensionDirectoryRoot = $this->container->getParameter('extension_directory');
                $file->move($extensionDirectoryRoot, $fileName);
            }else{
                $file->move($this->getTargetDirectory(), $fileName);
            }
        } catch (FileException $e) {
            return null;
        }

        return $fileName;
    }



    /**
     * @return mixed
     */
    public function getTargetDirectory()
    {
        return $this->targetDirectory;
    }
}