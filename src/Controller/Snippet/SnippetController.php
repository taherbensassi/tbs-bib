<?php

namespace App\Controller\Snippet;

use App\Api\ApiGitlab;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\VarDumper\VarDumper;

/**
 * @Route("/admin/snippet")
 */
class SnippetController extends AbstractController
{
    /**
     * @var ApiGitlab
     */
    private $apiGitlab;

    /**
     * SnippetController constructor.
     * @param ApiGitlab $apiGitlab
     */
    public function __construct(ApiGitlab $apiGitlab)
    {
        $this->apiGitlab = $apiGitlab;
    }


    /**
     * @Route("/", name="snippet")
     */
    public function index(): Response
    {
        $gitlabSnippet = $this->apiGitlab->fetchGitLabSnippet();

        if($gitlabSnippet == null)
            $apiFailed = true;

        return $this->render('Dashboard/Snippet/index.html.twig', [
            'controller_name' => 'SnippetController',
            'gitlabSnippet' => $gitlabSnippet,
            'apiFailed' => $apiFailed ?? false,
        ]);
    }
}
