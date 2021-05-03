<?php


namespace App\Api;


use Symfony\Contracts\HttpClient\HttpClientInterface;

/**
 * Class ApiVersion
 * @package App\Api
 */
class ApiVersion
{
    /**
     * @var HttpClientInterface
     */
    private $apiVersionClient;

    /**
     * ApiVersion constructor.
     * @param HttpClientInterface $apiVersionClient
     */
    public function __construct(HttpClientInterface $apiVersionClient)
    {
        $this->apiVersionClient = $apiVersionClient;
    }

    /**
     * @return array|null
     * @throws \Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface
     */
    public function fetchTypo3VersionInformation(): ?array
    {
        $response = $this->apiVersionClient->request(
            'GET',
            'https://get.typo3.org/v1/api/major'
        );

        $statusCode = $response->getStatusCode();
        if(200 === $statusCode){
            // $statusCode = 200
            $content = $response->getContent();
            // $content = '{"id":521583, "name":"symfony-docs", ...}'
            $content = $response->toArray();
            // $content = ['id' => 521583, 'name' => 'symfony-docs', ...]
            return $content;
        }else{
            return null;
        }
    }
}