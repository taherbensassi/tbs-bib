<?php

namespace App\Controller\Extension;

use App\Api\ApiGitlab;
use App\Api\ApiVersion;
use App\Entity\TbsExtension;
use App\Form\TbsExtensionType;
use App\Repository\TbsExtensionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/tbs-extension")
 */
class TbsExtensionController extends AbstractController
{
    /**
     * @var ApiVersion
     */
    private $apiVersion;

    /**
     * TbsExtensionController constructor.
     * @param ApiVersion $apiVersion
     */
    public function __construct(ApiVersion $apiVersion)
    {
        $this->apiVersion = $apiVersion;
    }


    /**
     * @Route("/", name="tbs_extension_index", methods={"GET"})
     */
    public function index(TbsExtensionRepository $tbsExtensionRepository): Response
    {
        return $this->render('Dashboard/Extension/index.html.twig', [
            'tbs_extensions' => $tbsExtensionRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="tbs_extension_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $tbsExtension = new TbsExtension();
        $form = $this->createForm(TbsExtensionType::class, $tbsExtension);
        $form->handleRequest($request);
        $typo3Version = $this->apiVersion->fetchTypo3VersionInformation();
        if(null === $typo3Version){
            $apiVersionFailed = true;
        }

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($tbsExtension);
            $entityManager->flush();

            return $this->redirectToRoute('tbs_extension_index');
        }

        return $this->render('Dashboard/Extension/new.html.twig', [
            'tbs_extension' => $tbsExtension,
            'form' => $form->createView(),
            'typo3Version' => $typo3Version,
            'apiVersionFailed' => $apiVersionFailed ?? false,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="tbs_extension_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, TbsExtension $tbsExtension): Response
    {
        $form = $this->createForm(TbsExtensionType::class, $tbsExtension);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('tbs_extension_index');
        }

        return $this->render('tbs_extension/edit.html.twig', [
            'tbs_extension' => $tbsExtension,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="tbs_extension_delete", methods={"POST"})
     */
    public function delete(Request $request, TbsExtension $tbsExtension): Response
    {
        if ($this->isCsrfTokenValid('delete'.$tbsExtension->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($tbsExtension);
            $entityManager->flush();
        }

        return $this->redirectToRoute('tbs_extension_index');
    }
}
