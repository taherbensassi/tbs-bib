<?php

namespace App\Controller\Module;

use App\Entity\TbsModule;
use App\Form\TbsModuleType;
use App\Repository\TbsModuleRepository;
use App\Service\FileUploader;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\VarDumper\VarDumper;

/**
 * @Route("/admin/tbs-module")
 */
class TbsModuleController extends AbstractController
{
    /**
     * @Route("/", name="tbs_module_index", methods={"GET"})
     */
    public function index(TbsModuleRepository $tbsModuleRepository): Response
    {
        return $this->render('Dashboard/Content Elements/Brettinghams/index.html.twig', [
            'tbs_modules' => $tbsModuleRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="tbs_module_new", methods={"GET","POST"})
     */
    public function new(Request $request,FileUploader $fileUploader): Response
    {
        $tbsModule = new TbsModule();
        $form = $this->createForm(TbsModuleType::class, $tbsModule);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile $image */
            $image = $form->get('image')->getData();
            if ($image) {
                $imageFileName = $fileUploader->upload($image);
                $tbsModule->setImage($imageFileName);
            }
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($tbsModule);
            $entityManager->flush();

            return $this->redirectToRoute('tbs_module_index');
        }

        return $this->render('Dashboard/Content Elements/Brettinghams/new.html.twig', [
            'tbs_module' => $tbsModule,
            'form' => $form->createView(),
        ]);
    }



    /**
     * @Route("/{id}/edit", name="tbs_module_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, TbsModule $tbsModule,FileUploader $fileUploader): Response
    {
        $form = $this->createForm(TbsModuleType::class, $tbsModule);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile $image */
            $image = $form->get('image')->getData();
            if ($image) {
                $imageFileName = $fileUploader->upload($image);
                $tbsModule->setImage($imageFileName);
            }
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('tbs_module_index');
        }

        return $this->render('Dashboard/Content Elements/Brettinghams/edit.html.twig', [
            'tbs_module' => $tbsModule,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="tbs_module_delete", methods={"POST"})
     */
    public function delete(Request $request, TbsModule $tbsModule): Response
    {
        if ($this->isCsrfTokenValid('delete'.$tbsModule->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();

            $filesystem = new Filesystem();
            $currentDirPath = getcwd();
            $filesystem->remove(['unlink',$currentDirPath.'/uploads/images/'.$tbsModule->getImage()]);

            $entityManager->remove($tbsModule);
            $entityManager->flush();
        }

        return $this->redirectToRoute('tbs_module_index');
    }
}
