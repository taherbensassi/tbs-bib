<?php

namespace App\Controller;

use App\Api\ApiGitlab;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/gitlab")
 */
class GitlabController extends AbstractController
{
    /**
     * @var ApiGitlab
     */
    private $apiGitlab;

    /**
     * GitlabController constructor.
     * @param ApiGitlab $apiGitlab
     */
    public function __construct(ApiGitlab $apiGitlab)
    {
        $this->apiGitlab = $apiGitlab;
    }

    /**
     * @Route("/users", name="gitlab_users")
     */
    public function gitlabUsers(): Response
    {
        $gitlabUsers= $this->apiGitlab->fetchGitLabUsers(100,'desc');

        return $this->render('Dashboard/Gitlab/Users/index.html.twig', [
            'controller_name' => 'GitlabController',
            'gitlabUsers' => $gitlabUsers,
        ]);
    }

    /**
     * @Route("/projects", name="gitlab_project")
     */
    public function gitlabProjects(): Response
    {
        $gitlabProjects = $this->apiGitlab->fetchGitLabProjects(100);

        return $this->render('Dashboard/Gitlab/Projects/index.html.twig', [
            'controller_name' => 'GitlabController',
            'gitlabProjects' => $gitlabProjects,
        ]);
    }
}
