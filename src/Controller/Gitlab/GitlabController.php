<?php

namespace App\Controller\Gitlab;

use App\Api\ApiGitlab;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\VarDumper\VarDumper;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

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
     * @param bool $apiFailed
     */
    public function __construct(ApiGitlab $apiGitlab)
    {
        $this->apiGitlab = $apiGitlab;
    }


    /**
     * @Route("/users", name="gitlab_users")
     */
    public function gitlabUsers(Request $request,PaginatorInterface $paginator): Response
    {
        $gitlabUsers = $this->apiGitlab->fetchGitLabUsers(100, 'desc');

        if($gitlabUsers == null)
            $apiFailed = true;

        $pagination = $paginator->paginate(
            $gitlabUsers, /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            9 /*limit per page*/
        );
        $pagination->setCustomParameters([
            'align' => 'left', # center|right (for template: twitter_bootstrap_v4_pagination and foundation_v6_pagination)
        ]);

        return $this->render('Dashboard/Gitlab/Users/index.html.twig', [
            'controller_name' => 'GitlabController',
            'gitlabUsers' => $pagination,
            'apiFailed' => $apiFailed ?? false,
        ]);
    }

    /**
     * @Route("/projects", name="gitlab_project")
     */
    public function gitlabProjects(): Response
    {
        $gitlabProjects = $this->apiGitlab->fetchGitLabProjects(100);

        if($gitlabProjects == null)
            $apiFailed = true;

        return $this->render('Dashboard/Gitlab/Projects/index.html.twig', [
            'controller_name' => 'GitlabController',
            'gitlabProjects' => $gitlabProjects,
            'apiFailed' => $apiFailed ?? false,
        ]);
    }


    /**
     * @param string $username
     * @return Response
     * @Route("/users/detail/{username}", name="gitlab_user_detail")
     */
    public function gitlabUserDetail(string $username): Response
    {
        $user = $this->apiGitlab->fetchGitLabUser($username);
        $userProject = $this->apiGitlab->fetchGitLabUserProject($user[0]['id']);
        $userPushedStats = $this->apiGitlab->fetchGitLabByUserStats($user[0]['id'],'pushed');


        return $this->render('Dashboard/Gitlab/Users/Detail/index.html.twig', [
            'controller_name' => 'GitlabController',
            'username' => $username,
            'user' => $user[0],
            'userProject' => $userProject,
            'userPushedStats' => $userPushedStats,
        ]);
    }



}
