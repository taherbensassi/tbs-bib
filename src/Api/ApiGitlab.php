<?php


namespace App\Api;


use phpDocumentor\Reflection\Types\Mixed_;
use Symfony\Component\HttpClient\CachingHttpClient;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpKernel\HttpCache\Store;
use Symfony\Component\VarDumper\VarDumper;
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
     * @return array|null
     * @throws ClientExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     */
    public function fetchGitLabStats(): ?array
    {
        $response = $this->gitlab->request('GET', 'https://gitlab.brettinghams-dev.de/api/v4/application/statistics', []);
        if (200 === $response->getStatusCode()) {
            // $contentType = 'application/json'
            return $response->toArray();
        }
        return null;
    }

    /**
     * @param int $page
     * @return array|null
     * @throws ClientExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     */
    public function fetchGitLabProjects(int $page): ?array
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
        }
        return null;
    }

    /**
     * @param int $page
     * @param string $sort
     * @return array|null
     * @throws ClientExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     */
    public function fetchGitLabUsers(int $page, string $sort): ?array
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
        }
        return null;
    }

    /**
     * @param string $username
     * @return array|null
     * @throws ClientExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     */
    public function fetchGitLabUser(string $username): ?array
    {
        $response = $this->gitlab->request('GET', 'https://gitlab.brettinghams-dev.de/api/v4/users', [
            'query' => [
                'username' => $username,
            ],
        ]);
        if (200 === $response->getStatusCode()) {
            // $contentType = 'application/json'
            return $response->toArray();
        }
        return null;
    }

    /**
     * @param int $id
     * @return string|null
     * @throws ClientExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     */
    public function fetchGitLabUserProject(int $id): ?string
    {
        $response = $this->gitlab->request('GET', 'https://gitlab.brettinghams-dev.de/api/v4/users/'.$id.'/projects', []);
        if (200 === $response->getStatusCode()) {
            // $contentType = 'application/json'
            return $response->getHeaders()['x-total'][0];

        }
        return null;
    }

    /**
     * @return array|null
     * @throws ClientExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     */
    public function fetchGitLabUsersPushedStats(): ?array
    {
        $data = [];
        $users = $this->fetchGitLabUsers(40,'asc');
        if(null !== $users){
            foreach ($users as $key => $user){
                $response = $this->gitlab->request('GET', 'https://gitlab.brettinghams-dev.de/api/v4/users/'.$user['id'].'/events', [
                    'query' => [
                        'action' => 'pushed',
                    ],
                ]);
                if (200 === $response->getStatusCode()) {
                    // $contentType = 'application/json'
                    $contentType = $response->getHeaders()['x-total'][0];

                    $data[$key]['user'] = $user['username'];
                    $data[$key]['pushedNumber'] = $contentType;
                }
            }
            return $data;
        }
        return null;
    }


    /**
     * @param int $id
     * @param string $action
     * @return string|null
     * @throws ClientExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     */
    public function fetchGitLabByUserStats(int $id,string $action): ?string
    {
        $response = $this->gitlab->request('GET', 'https://gitlab.brettinghams-dev.de/api/v4/users/'.$id.'/events', [
            'query' => [
                'action' => $action,
            ],
        ]);
        if (200 === $response->getStatusCode()) {
            return  $response->getHeaders()['x-total'][0];
        }
        return null;
    }
}