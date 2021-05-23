<?php


namespace App\Controller\Export\Implementations;


use Psr\Container\ContainerInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Iterator\RecursiveDirectoryIterator;
use Symfony\Component\Finder\SplFileInfo;
use Symfony\Component\VarDumper\VarDumper;

/**
 * Class ExportServiceInterfaceImpl
 * @package App\Controller\Export\Implementations
 */
class ExportServiceInterfaceImpl implements \App\Controller\Export\Interfaces\ExportServiceInterface
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
     * @param string $content
     * @param string $CType
     * @return mixed|void
     */
    public function exportTsConfig(string $content, string $CType)
    {
        $fileName = 'tbscontentelements_'.$CType.'.typoscript';
        $TsConfigDirectoryRoot = $this->container->getParameter('tbs_content_element_directory_tsconfig');
        return $this->export($TsConfigDirectoryRoot,$fileName,$content);
    }

    /**
     * @param string $root
     * @param string $fileName
     * @param string $content
     * @param bool $checkExist
     * @return bool
     */
    public function export(string $root, string $fileName, string $content,bool $checkExist = true) :bool
    {
        $filesystem = new Filesystem();
        //-- Errors handling
        if((null != $root) && (null != $fileName) && (null != $content)) {
            if(true === $checkExist){
                if (!$filesystem->exists($this->currentDirPath.$root.$fileName)){
                    $filesystem->touch($this->currentDirPath.$root.$fileName);
                    $filesystem->appendToFile($this->currentDirPath.$root.$fileName, $content);
                    return true;
                }
            }else{
                $filesystem->touch($this->currentDirPath.$root.$fileName);
                $filesystem->appendToFile($this->currentDirPath.$root.$fileName, $content);
                return true;
            }
        }
        return false;
    }

    /**
     * @param string $content
     * @param string $CType
     * @return mixed
     */
    public function exportTypoScript(string $content, string $CType)
    {
        $fileName = 'tbscontentelements_'.$CType.'.typoscript';
        $TypScriptConfigDirectoryRoot = $this->container->getParameter('tbs_content_element_directory_typoscript');
        return $this->export($TypScriptConfigDirectoryRoot,$fileName,$content);
    }

    /**
     * @param string $content
     * @param string $CType
     * @return mixed
     */
    public function exportTtContent(string $content, string $CType)
    {
        $fileName = 'tbscontentelements_'.$CType.'.php';
        $TtContentRoot = $this->container->getParameter('tbs_content_element_directory_tt_content');

        $created = $this->export($TtContentRoot,$fileName,$content);
        if (false != $created){
            $ttContentRootOverride = $this->container->getParameter('tbs_content_element_directory_tt_content_override');
            return $this->export($ttContentRootOverride,'tt_content.php',"\r\ninclude_once 'TBS-Module/".$fileName."';",false);
        }
        return  false;
    }

    /**
     * @param string $content
     * @param string $CType
     * @param int $type
     * @return mixed
     */
    public function exportSql(string $content, string $CType, int $type)
    {
        $sqlCodeRoot = $this->container->getParameter('tbs_content_element_directory_sql');
        if(1 === $type) {
            if(null != $sqlCodeRoot){
                $file                = file($this->currentDirPath . $sqlCodeRoot . "ext_tables.sql");
                $firstLine           = array_shift($file);
                array_unshift($file, $content);
                array_unshift($file, $firstLine);
                $fp = fopen($this->currentDirPath . $sqlCodeRoot . "ext_tables.sql", 'w');
                fwrite($fp, implode("\n", $file));
                fclose($fp);
                return true;

            }else{
                return false;
            }
        }else if(2 === $type){
            return $this->export($sqlCodeRoot,'ext_tables.sql',$content,false);

        }
        return false;
    }

    /**
     * @param string $content
     * @param string $CType
     * @param string $fileNameContent
     * @param int $type
     * @return mixed
     */
    public function exportHtml(string $content,string $CType,string $fileNameContent,int $type)
    {
        $indexFile = strpos($fileNameContent,"file");

        if ($indexFile) {
            //- get the the index of .html
            $htmlIndex = strpos($fileNameContent, '.html');
            $path = substr($fileNameContent, $indexFile, ($htmlIndex - $indexFile) + 5);
            $fileName = basename($path);
            if ($this->validFilename($fileName)) {
                //--  1 = Backend
                if (1 === $type) {
                    $htmlBackendRoot = $this->container->getParameter('tbs_content_element_directory_html_backend');
                    return $this->export($htmlBackendRoot,$fileName,$content);
                } else {
                    // 2 = frontend
                    $htmlFrontendRoot = $this->container->getParameter('tbs_content_element_directory_html_frontend');
                    return $this->export($htmlFrontendRoot,$fileName,$content);
                }
            } else {
                return false;
            }
        }
        return false;
    }


    /**
     * @param string $content
     * @param string $CType
     * @param int $type
     * @return mixed
     */
    public function exportXml(string $content,string $CType,int $type)
    {
        $xmlCodeRoot = $this->container->getParameter('tbs_content_element_directory_language');
        //-- type 1 = en
        if (1 === $type) {
            $fileName = $this->currentDirPath . $xmlCodeRoot . "locallang.xlf";
        //-- type 2 = de
        } else {
            $fileName = $this->currentDirPath . $xmlCodeRoot . "de.locallang.xlf";
        }
        $specific_line = 9;
        $contents = file($fileName, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        if($specific_line > sizeof($contents)) {
            $specific_line = sizeof($contents) + 1;
        }
        array_splice($contents, $specific_line-1, 0, array($content));
        $contents = implode("\n", $contents);
        file_put_contents($fileName, $contents);
        return true;
    }

    /**
     * @return mixed
     */
    public function createZip()
    {
        /*
    // Initialize archive object
    $zip = new \ZipArchive();
    $zip->open('test.zip', \ZipArchive::CREATE | \ZipArchive::OVERWRITE);

// Create recursive directory iterator
    /** @var SplFileInfo[] $files
    $files = new \RecursiveIteratorIterator(new RecursiveDirectoryIterator($currentDirPath.'/typo3-ce-extension/tbs_content_elements',1), \RecursiveIteratorIterator::LEAVES_ONLY,1);

    foreach ($files as $name => $file)
    {
        // Get real and relative path for current file
        $filePath = $file->getRealPath();
        $relativePath = substr($filePath, strlen($currentDirPath.'/typo3-ce-extension/tbs_content_elements') + 1);

        if (!$file->isDir())
        {
            // Add current file to archive
            $zip->addFile($filePath, $relativePath);
        }else {
            if($relativePath !== false)
                $zip->addEmptyDir($relativePath);
        }
    }

// Zip archive will be created only after closing object
    $zip->close();
    */
    }


    /**
     * @param string $filename
     * @return bool
     */
    function validFilename(string $filename): bool
    {
        if (strlen($filename) > 255) {
            return false;
        }
        $html = array('html');
        $pattern = '/^[^`~!@#$%^&*()+=[\];\',.\/?><":}{]+\.(' . implode('|', $html). ')$/u';

        if(preg_match($pattern, $filename)) {
            return true;
        }
        return false;
    }


}