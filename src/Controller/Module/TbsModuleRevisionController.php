<?php

namespace App\Controller\Module;

use App\Repository\TbsModuleRepository;
use App\Service\FileUploader;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\VarDumper\VarDumper;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/admin/tbs-module-revision")
 */
class TbsModuleRevisionController extends AbstractController
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
        return $this->render('Dashboard/Content Elements/Revision/index.html.twig', [
            'controller_name' => 'TbsModuleTestController',
            'tbs_modules' => $this->tbsModuleRepository->findAll(),
        ]);
    }

    /**
     * @Route(path="/change-status", name="tbs_module_change_status",methods={"POST"})
     */
    public function changeStatus(Request $request): JsonResponse
    {
        $entityManager = $this->getDoctrine()->getManager();
        if (!$request->isXmlHttpRequest()) {
            return new JsonResponse(array(
                'status' => 'Error',
                'message' => 'Error'),
                400);
        }
        if ($request->isXmlHttpRequest()) {
            // Get data from ajax
            $tbsModuleId = $request->get('id');
            $status = $request->get('status');
            $id = intval($tbsModuleId);
            if ($id <= 0)
            {
                return new JsonResponse(array(
                    'status' => 'Error',
                    'message' => 'Error'),
                    400);
            }
            $module = $this->tbsModuleRepository->find($id);
            if (null === $module)
            {
                // Looks like something went wrong
                return new JsonResponse(array(
                    'status' => 'Error',
                    'message' => 'Error'),
                    400);
            }
            $module->setStatus((int)filter_var($status, FILTER_VALIDATE_BOOLEAN));
            $entityManager->persist($module);
            $entityManager->flush();
            return new JsonResponse(array(
                'status' => 'OK',
                'message' => 'success'),
                200);
        }
        return new JsonResponse(array(
            'status' => 'Error',
            'message' => 'Error'),
            400);
    }
}
