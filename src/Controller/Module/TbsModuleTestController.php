<?php

namespace App\Controller\Module;

use App\Repository\TbsModuleRepository;
use App\Service\FileUploader;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/tbs-module-revision")
 */
class TbsModuleTestController extends AbstractController
{
    /**
     * @var TbsModuleRepository
     */
    private $tbsModuleRepository;

    /**
     * TbsModuleTestController constructor.
     * @param TbsModuleRepository $tbsModuleRepository
     */
    public function __construct(TbsModuleRepository $tbsModuleRepository)
    {
        $this->tbsModuleRepository = $tbsModuleRepository;
    }


    /**
     * @Route("/", name="tbs_module_test")
     */
    public function index(): Response
    {
        return $this->render('Dashboard/Content Elements/Test/index.html.twig', [
            'controller_name' => 'TbsModuleTestController',
            'tbs_modules' => $this->tbsModuleRepository->findAll(),
        ]);
    }

    /**
     * @Route("/change-status", name="tbs_module_change_status", methods={"GET","POST"})
     */
    public function changeStatus(){
        echo "ok";
    }
}
