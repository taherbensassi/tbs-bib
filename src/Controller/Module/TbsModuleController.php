<?php

namespace App\Controller\Module;

use App\Entity\TbsModule;
use App\Form\TbsModuleType;
use App\Repository\ContentElementsRepository;
use App\Repository\FileRepository;
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
     * @var FileRepository
     */
    private $fileRepository;

    /**
     * TbsModuleController constructor.
     * @param FileRepository $fileRepository
     */
    public function __construct(FileRepository $fileRepository)
    {
        $this->fileRepository = $fileRepository;
    }


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

        $entityManager = $this->getDoctrine()->getManager();

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile $image */
            $image = $form->get('previewImageFileName')->getData();
            /** @var UploadedFile $images */
            $images = $form->get('moduleImages')->getData();

            if ($image) {
                $imageFileName = $fileUploader->upload($image);
                $tbsModule->setPreviewImageFileName($imageFileName);
            }
            if ($images) {
                foreach ($images as $img){
                    $imageFileName = $fileUploader->upload($img);
                    $file = new \App\Entity\File();
                    $file->setImageName($imageFileName);
                    $file->setModule($tbsModule);
                    $file->setImageSize($img->getSize());
                    $entityManager->persist($file);
                    $entityManager->flush();

                }

            }
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
        $entityManager = $this->getDoctrine()->getManager();

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile $image */
            $image = $form->get('previewImageFileName')->getData();
            /** @var UploadedFile $images */
            $images = $form->get('moduleImages')->getData();
            if ($image) {
                $imageFileName = $fileUploader->upload($image);
                $tbsModule->setPreviewImageFileName($imageFileName);
            }
            if ($images) {
                foreach ($images as $img){
                    $imageFileName = $fileUploader->upload($img);
                    $file = new \App\Entity\File();
                    $file->setImageName($imageFileName);
                    $file->setModule($tbsModule);
                    $file->setImageSize($img->getSize());
                    $entityManager->persist($file);
                    $entityManager->flush();

                }

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
     * @Route("/{id}", name="tbs_module_show", methods={"GET"})
     */
    public function show(TbsModule $tbsModule): Response
    {
        return $this->render('Dashboard/Content Elements/Brettinghams/show.html.twig', [
            'tbs_module' => $tbsModule,
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
            $filesystem->remove(['unlink',$currentDirPath.'/uploads/images/'.$tbsModule->getPreviewImageFileName()]);

            $files = $this->fileRepository->findBy([
                    'module' => $tbsModule->getId()
            ]);
            if(!empty($files)){
                foreach ($files as $file){
                    $filesystem->remove(['unlink',$currentDirPath.'/uploads/images/'.$file->getImageName()]);
                }
            }
            $entityManager->remove($tbsModule);
            $entityManager->flush();
        }

        return $this->redirectToRoute('tbs_module_index');
    }
}
