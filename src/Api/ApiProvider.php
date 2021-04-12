<?php


namespace App\Api;


use Symfony\Component\Filesystem\Exception\IOExceptionInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpClient\Response\StreamWrapper;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\VarDumper\VarDumper;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class ApiProvider
{

    /**
     * @param HttpClientInterface $client
     * @param String $apiUrl
     * @param String $method
     * @param String $body
     * @param String $title
     * @return string
     * @throws \Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface
     */
    public function fetchSitePackageInformation(HttpClientInterface $client , String $apiUrl, String $method, String $body, String $title): string
    {
        try {
            $response = $client->request($method, $apiUrl, [
                'body' => $body,
                'headers' => [
                    'Accept' => 'application/json',
                ],
            ]);
            $statusCode = $response->getStatusCode();
            if($statusCode === 200){
                $contentDisposition = $response->getHeaders()['content-disposition'][0];
                $content = $response->getContent();

                $filesystem = new Filesystem();
                $currentDirPath = getcwd();
                $currentTime = time();
                if((null != $this->getFilename($contentDisposition)) && (null != $currentDirPath)){
                    // create a new file and add contents
                    $extenionName = $this->getFilename($contentDisposition);
                    try {
                        $path =  "/uploads/package/".$extenionName;
                        $newFilePath = $currentDirPath . $path;
                        if (!$filesystem->exists($newFilePath))
                        {
                            $filesystem->appendToFile($newFilePath, $content);
                        }else{
                            $path = "/uploads/package/".$currentTime."-".$extenionName;
                            $newFilePath = $currentDirPath . $path;
                            $filesystem->appendToFile($newFilePath, $content);
                        }
                        return $path;
                    } catch (IOExceptionInterface $e) {
                        return "Error creating file at". $e->getPath();
                    }
                }
            }

        } catch (TransportExceptionInterface $e) {
            return "Error Api". $e->getPath();
        }
        return false;
    }


    /**
     * @param $header
     * @return string|null
     */
    function getFilename($header): ?string
    {
        if (preg_match('/filename="(.+?)"/', $header, $matches)) {
            return $matches[1];
        }
        if (preg_match('/filename=([^; ]+)/', $header, $matches)) {
            return rawurldecode($matches[1]);
        }
        return null;
    }
}