<?php


namespace App\Controller\Export\Implementations;


use Psr\Container\ContainerInterface;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\Mime\FileinfoMimeTypeGuesser;
use Symfony\Component\VarDumper\VarDumper;

/**
 * Class ZipServiceInterfaceImpl
 * @package App\Controller\Export\Implementations
 */
class ZipServiceInterfaceImpl implements \App\Controller\Export\Interfaces\ZipServiceInterface
{

    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * @var string|false
     */
    private $currentDirPath;

    /**
     * ExportServiceInterfaceImpl constructor.
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
        $this->currentDirPath = getcwd();
    }

    /**
     * @return mixed
     */
    public function createZip(){
        // Initialize archive object
        $zip = new \ZipArchive();
        $zip->open('tbs_content_elements.zip', \ZipArchive::CREATE | \ZipArchive::OVERWRITE);

        /** @var \SplFileInfo[] $files **/
        $files = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($this->currentDirPath.'/typo3-ce-extension/tbs_content_elements',1), \RecursiveIteratorIterator::LEAVES_ONLY,1);

        foreach ($files as $name => $file)
        {
            // Get real and relative path for current file
            $filePath = $file->getRealPath();
            $relativePath = substr($filePath, strlen($this->currentDirPath.'/typo3-ce-extension/tbs_content_elements') + 1);

            if (!$file->isDir())
            {
                // Add current file to archive
                $zip->addFile($filePath, $relativePath);
            }else {
                if($relativePath !== false)
                    $zip->addEmptyDir($relativePath);
            }
        }

        $zip->close();

        return $zip;
    }

    /**
     * @return mixed
     */
    public function unsetTbsExtension()
    {
        $filesystem = new Filesystem();
        $removeRoot = $this->container->getParameter('tbs_content_element_directory_project_init');
        try {
            $filesystem->remove(['unlink', $this->currentDirPath.$removeRoot]);
        } catch (IOExceptionInterface $exception) {
            return "Fehler ".$exception->getPath();
        }
        return true;
    }

    /**
     * @return mixed
     */
    public function initialize()
    {
        $filesystem = new Filesystem();
        $mirrorFrom = $this->container->getParameter('tbs_content_element_directory_project');
        $mirrorTo   = $this->container->getParameter('tbs_content_element_directory_project_init');
        try {
            $filesystem->mirror($this->currentDirPath.$mirrorFrom, $this->currentDirPath.$mirrorTo);
        } catch (IOExceptionInterface $exception) {
            echo "Beim Erstellen Ihres Verzeichnisses ist ein Fehler aufgetreten bei ".$exception->getPath();
        }
    }

    /**
     * @return BinaryFileResponse
     */
    public function downloadExtension(): BinaryFileResponse
    {
        $zip        = $this->container->getParameter('tbs_content_element_directory_download');
        $fileName   = $zip.'/tbs_content_elements.zip';
        $fileNameZip   = 'tbs_content_elements.zip';

        // This should return the file to the browser as response
        $response = new BinaryFileResponse($fileName);

        // To generate a file download, you need the mimetype of the file
        $mimeTypeGuesser = new FileinfoMimeTypeGuesser();

        // Set the mimetype with the guesser or manually
        if($mimeTypeGuesser->isGuesserSupported()){
            // Guess the mimetype of the file according to the extension of the file
            $response->headers->set('Content-Type', $mimeTypeGuesser->guessMimeType($fileName));
        }else{
            // Set the mimetype of the file manually, in this case for a text file is text/plain
            $response->headers->set('Content-Type', 'text/plain');
        }

        // Set content disposition inline of the file
        $response->setContentDisposition(
            ResponseHeaderBag::DISPOSITION_ATTACHMENT,
            $fileNameZip
        );

        //delete file after send response
        $response->deleteFileAfterSend(true);
        return $response;
    }
}