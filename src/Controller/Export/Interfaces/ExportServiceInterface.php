<?php


namespace App\Controller\Export\Interfaces;


/**
 * Interface ExportServiceInterface
 * @package App\Controller\Export\Service
 */
interface ExportServiceInterface
{


    /**
     *
     * This function will export the content to a file
     *
     *
     * @param string $root
     * @param string $fileName
     * @param string $content
     * @param bool $checkexist
     * @return mixed
     */
    public function export(string $root,string $fileName,string $content,bool $checkexist = true);

    /**
     *
     * This function will export the content of the tsConfig into file
     *
     * @param string $content
     * @param string $CType
     * @return mixed
     */
    public function exportTsConfig(string $content,string $CType);

    /**
     *
     * This function will export the content of the TypoScript into file
     *
     * @param string $content
     * @param string $CType
     * @return mixed
     */
    public function exportTypoScript(string $content,string $CType);

    /**
     *
     * This function will export the content of the TtContent into file
     *
     * @param string $content
     * @param string $CType
     * @return mixed
     */
    public function exportTtContent(string $content,string $CType);

    /**
     *
     * This function will export the content of the Sql into file
     *
     * @param string $content
     * @param string $CType
     * @param int $type
     * @return mixed
     */
    public function exportSql(string $content,string $CType, int $type);

    /**
     *
     * This function will export the content of the HTML-Frontend into file
     *
     * @param string $content
     * @param string $CType
     * @param string $fileNameContent
     * @param int $type
     * @return mixed
     */
    public function exportHtml(string $content,string $CType,string $fileNameContent,int $type);

    /**
     *
     * This function will export the content of the Sql into file
     *
     * @param string $content
     * @param string $CType
     * @param int $type
     * @return mixed
     */
    public function exportXml(string $content,string $CType,int $type);

    /**
     * @param string $filename
     * @return mixed
     */
    function validFilename(string $filename);

    /**
     * @param array $selectedModules
     * @return mixed
     */
    function generateModuleIcons(array $selectedModules);

    /**
     * @param array $selectedModules
     * @return mixed
     */
    function generateModuleBackendPreview(array $selectedModules);

}