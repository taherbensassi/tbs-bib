<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class DashboardController extends AbstractController
{
    private $gitlab;

    public function __construct(HttpClientInterface $gitlab)
    {
        $this->gitlab = $gitlab;
    }


    /**
     * @Route("/admin/dashboard", name="dashboard")
     */
    public function index(): Response
    {



        return $this->render('Dashboard/Index/index.html.twig', [
            'controller_name' => 'DashboardController',
        ]);
    }
}
