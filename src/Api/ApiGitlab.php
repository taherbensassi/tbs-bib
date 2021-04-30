<?php


namespace App\Api;


use Symfony\Component\HttpClient\CachingHttpClient;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpKernel\HttpCache\Store;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

/**
 * Class ApiGitlab
 * @package App\Api
 */
class ApiGitlab
{
    /**
     * @var HttpClientInterface
     */
    private $gitlab;

    /**
     * ApiGitlab constructor.
     * @param HttpClientInterface $gitlab
     */
    public function __construct(HttpClientInterface $gitlab)
    {
        $this->gitlab = $gitlab;
    }

    /**
     * @return array
     * @throws ClientExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     */
    public function fetchGitLabStats(): array
    {
        $response = $this->gitlab->request('GET', 'https://gitlab.brettinghams-dev.de/api/v4/application/statistics', []);
        if (200 === $response->getStatusCode()) {
            // $contentType = 'application/json'
            return $response->toArray();
        }else{
            throw new \Exception('Konnte keine Verbindung zu Gitlab herstellen');
        }
    }

    /**
     * @param int $page
     * @return array
     * @throws ClientExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     */
    public function fetchGitLabProjects(int $page): array
    {
        $response = $this->gitlab->request('GET', 'https://gitlab.brettinghams-dev.de/api/v4/projects', [
            'query' => [
                'per_page' => $page,
                'order_by' => 'created_at',
                'statistics' => 'yes',
            ],
        ]);
        if (200 === $response->getStatusCode()) {
            // $contentType = 'application/json'
            return $response->toArray();
        }else{
            throw new \Exception('Konnte keine Verbindung zu Gitlab herstellen');
        }
    }


    /**
     * @param int $page
     * @param string $sort
     * @return array
     * @throws ClientExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     */
    public function fetchGitLabUsers(int $page, string $sort): array
    {
        $response = $this->gitlab->request('GET', 'https://gitlab.brettinghams-dev.de/api/v4/users', [
            'query' => [
                'order_by' => 'created_at',
                'per_page' => $page,
                'sort' => $sort,
            ],
        ]);
        if (200 === $response->getStatusCode()) {
            // $contentType = 'application/json'
            return $response->toArray();
        }else{
            throw new \Exception('Konnte keine Verbindung zu Gitlab herstellen');
        }
    }
}