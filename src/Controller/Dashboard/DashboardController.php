<?php

namespace App\Controller\Dashboard;

use App\Api\ApiGitlab;
use App\Repository\ClientRepository;
use App\Repository\ContentElementsRepository;
use App\Repository\UserRepository;
use App\Service\LoggedInUserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\VarDumper\VarDumper;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class DashboardController extends AbstractController
{
    /**
     * @var ContentElementsRepository
     */
    private $contentElementRepository;

    /**
     * @var UserRepository
     */
    private $usersRepository;

    /**
     * @var ClientRepository
     */
    private $clientRepository;

    /**
     * @var ApiGitlab
     */
    private $apiGitlab;

    /**
     * DashboardController constructor.
     * @param ContentElementsRepository $contentElementRepository
     * @param UserRepository $usersRepository
     * @param ClientRepository $clientRepository
     * @param ApiGitlab $apiGitlab
     */
    public function __construct(ContentElementsRepository $contentElementRepository, UserRepository $usersRepository, ClientRepository $clientRepository, ApiGitlab $apiGitlab)
    {
        $this->contentElementRepository = $contentElementRepository;
        $this->usersRepository = $usersRepository;
        $this->clientRepository = $clientRepository;
        $this->apiGitlab = $apiGitlab;
    }


    /**
     * @Route("/admin/dashboard", name="dashboard")
     */
    public function index(): Response
    {
        // content Element
        $contentElement = $this->contentElementRepository->findAll();
        $contentsElementCount = count($contentElement)  ?? 0;

        // Users
        $users = $this->usersRepository->findAll();
        $usersCount = count($users)  ?? 0;

        // clients
        $clients = $this->usersRepository->findAll();
        $clientsCount = count($clients)  ?? 0;

        $gitlabStats = $this->apiGitlab->fetchGitLabStats();
        $gitlabProject = $this->apiGitlab->fetchGitLabProjects(10);
        $gitlabUsers= $this->apiGitlab->fetchGitLabUsers(8,'asc');

        if ($gitlabStats == null)
            $gitlabStatsFailed = true;

        if ($gitlabProject == null)
            $gitlabProjectFailed = true;

        if ($gitlabUsers == null)
            $gitlabUsersFailed = true;

        return $this->render('Dashboard/Index/index.html.twig', [
            'controller_name' => 'DashboardController',
            'contentElementCount' => $contentsElementCount,
            'usersCount' => $usersCount,
            'clientsCount' => $clientsCount,
            'gitlabStats' => json_encode($gitlabStats),
            'gitlabProjects' => $gitlabProject,
            'gitlabUsers' => $gitlabUsers,
            'gitlabStatsFailed' => $gitlabStatsFailed ?? false,
            'gitlabProjectFailed' => $gitlabProjectFailed ?? false,
            'gitlabUsersFailed' => $gitlabUsersFailed ?? false,
        ]);
    }
}
