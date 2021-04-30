<?php


namespace App\Api;


use Symfony\Component\HttpClient\CachingHttpClient;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpKernel\HttpCache\Store;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class ApiGitlab
{
    /**
     * @var HttpClientInterface
     */
    private $gitlab;

    public function __construct(HttpClientInterface $gitlab)
    {
        $this->gitlab = $gitlab;
    }

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

    public function fetchGitLabProjects(): array
    {
        $response = $this->gitlab->request('GET', 'https://gitlab.brettinghams-dev.de/api/v4/projects', [
            'query' => [
                'per_page' => '50',
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

}