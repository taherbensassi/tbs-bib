<?php


namespace App\Controller\Export\Implementations;


use App\Repository\TbsModuleRepository;
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
     * @var TbsModuleRepository
     */
    private $tbsModuleRepository;

    /**
     * ExportServiceInterfaceImpl constructor.
     * @param TbsModuleRepository $tbsModuleRepository
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container,TbsModuleRepository $tbsModuleRepository)
    {
        $this->container = $container;
        $this->currentDirPath = getcwd();
        $this->tbsModuleRepository = $tbsModuleRepository;
    }


    /**
     * @param string $content
     * @param string $CType
     * @return mixed|void
     */
    public function exportTsConfig(string $content, string $CType): bool
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
    public function exportTypoScript(string $content, string $CType): bool
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
    public function exportTtContent(string $content, string $CType): bool
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
    public function exportSql(string $content, string $CType, int $type): bool
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
    public function exportHtml(string $content,string $CType,string $fileNameContent,int $type): bool
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
    public function exportXml(string $content,string $CType,int $type): bool
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


    /**
     * @param array $selectedModules
     * @return mixed|void
     */
    function generateModuleIcons(array $selectedModules): bool
    {
        $content ="";
        $root = $this->container->getParameter('tbs_content_element_directory_project_init');
        $fileName = $this->currentDirPath . $root . "/ext_localconf.php";
        $specific_line = 6;
        $contents = file($fileName, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        if($specific_line > sizeof($contents)) {
            $specific_line = sizeof($contents) + 1;
        }

        //-- loop over all modules
        foreach ($selectedModules as $key => $selectedModule) {

            //-- get Module
            $module = $this->tbsModuleRepository->find($selectedModule);

            $CType = 'tbscontentelements_'.$module->getModuleKey();
            $content .=
                <<<EOS
\$icons = [
    '$CType' => 'tbs_contentelements_icon.svg',
];
EOS;
        }

        array_splice($contents, $specific_line-1, 0, array($content));
        $contents = implode("\n", $contents);
        file_put_contents($fileName, $contents);
        return true;

    }

    /**
     * @param array $selectedModules
     * @return mixed
     */
    function generateModuleBackendPreview(array $selectedModules)
    {
        $content ="";
        $root = $this->container->getParameter('tbs_content_element_directory_backend_preview_php');
        $fileName = $this->currentDirPath . $root . "PageLayoutViewDrawItem.php";
        $specific_line = 19;
        $contents = file($fileName, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        if($specific_line > sizeof($contents)) {
            $specific_line = sizeof($contents) + 1;
        }

        //-- loop over all modules
        foreach ($selectedModules as $key => $selectedModule) {

            //-- get Module
            $module = $this->tbsModuleRepository->find($selectedModule);

            $indexFile = strpos($module->getTypoScriptCode(),"file");

            if ($indexFile) {
                //- get the the index of .html
                $htmlIndex = strpos($module->getTypoScriptCode(), '.html');
                $path = substr($module->getTypoScriptCode(), $indexFile, ($htmlIndex - $indexFile) + 5);
                $templateFileName = basename($path);
                if ($this->validFilename($templateFileName)) {

            $CType = 'tbscontentelements_'.$module->getModuleKey();
            $content .=
                <<<EOS
protected \$supportedContentTypes = [
    '$CType' => '$templateFileName',
];
EOS;
        }else{
                return false;
                }
        }else{
                return false;
        }
    }
        array_splice($contents, $specific_line-1, 0, array($content));
        $contents = implode("\n", $contents);
        file_put_contents($fileName, $contents);
        return true;
    }
}