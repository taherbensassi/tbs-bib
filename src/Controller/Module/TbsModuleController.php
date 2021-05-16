<?php

namespace App\Controller\Module;

use App\Entity\TbsModule;
use App\Form\TbsModuleType;
use App\Repository\ContentElementsRepository;
use App\Repository\FileRepository;
use App\Repository\TbsModuleRepository;
use App\Service\FileUploader;
use App\Service\LoggedInUserService;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\VarDumper\VarDumper;



/**
 * @todo add display images by edit to remove with ajax
 * @Route("/admin/tbs-module")
 */
class TbsModuleController extends AbstractController
{

    /**
     * @var LoggedInUserService
     */
    private $loggedInUser;

    /**
     * @var FileRepository
     */
    private $fileRepository;

    /**
     * @var TbsModuleRepository
     */
    private $tbsModuleRepository;

    /**
     * TbsModuleController constructor.
     * @param LoggedInUserService $loggedInUser
     * @param FileRepository $fileRepository
     * @param TbsModuleRepository $tbsModuleRepository
     */
    public function __construct(LoggedInUserService $loggedInUser, FileRepository $fileRepository, TbsModuleRepository $tbsModuleRepository)
    {
        $this->loggedInUser= $loggedInUser->getUser();
        $this->fileRepository = $fileRepository;
        $this->tbsModuleRepository = $tbsModuleRepository;
    }

    /**
     * @Route("/", name="tbs_module_index", methods={"GET"})
     * @param TbsModuleRepository $tbsModuleRepository
     * @return Response
     */
    public function index(Request $request,PaginatorInterface $paginator): Response
    {
        $result = $this->tbsModuleRepository->findAll();

        if ($request->isMethod('GET'))  {
            $filterTitle = $request->query->get('filterTitle');
            $filterDate = $request->query->get('filterDate');
            if($filterTitle){
                $result = $this->tbsModuleRepository->filterByOrder($this->getSortParameter($filterTitle),'title');
            }
            if($filterDate){
                $result = $this->tbsModuleRepository->filterByOrder($this->getSortParameter($filterDate),'created');
            }
            $filterTypo3_11 = $request->query->get('filterTypo3-11-dev');
            if($filterTypo3_11){
                $result = $this->tbsModuleRepository->filerByVersion('11-dev');
            }

            $filterTypo3_10 = $request->query->get('filterTypo3-10');
            if($filterTypo3_10){
                $result = $this->tbsModuleRepository->filerByVersion('10 LTS');
            }
            $filterTypo3_9 = $request->query->get('filterTypo3-9');
            if($filterTypo3_9){
                $result = $this->tbsModuleRepository->filerByVersion('9 LTS');
            }
            $pagination = $paginator->paginate(
                $result, /* query NOT result */
                $request->query->getInt('page', 1), /*page number*/
                6 /*limit per page*/
            );
        }

        return $this->render('Dashboard/Content Elements/Brettinghams/index.html.twig', [
            'tbs_modules' => $pagination,
            'filterTitle' => $filterTitle ?? null,
            'filterDate' => $filterDate ?? null,
            'filterTypo3_11' => $filterTypo3_11 ?? null,
            'filterTypo3_10' => $filterTypo3_10 ?? null,
            'filterTypo3_9' => $filterTypo3_9 ?? null,
        ]);
    }

    /**
     * @Route("/new", name="tbs_module_new", methods={"GET","POST"})
     * @param Request $request
     * @param FileUploader $fileUploader
     * @return Response
     */
    public function new(Request $request,FileUploader $fileUploader): Response
    {
        $tbsModule = new TbsModule();
        $form = $this->createForm(TbsModuleType::class, $tbsModule);
        $form->handleRequest($request);

        $entityManager = $this->getDoctrine()->getManager();

        if ($form->isSubmitted() && $form->isValid()) {

            $checkFrom = $this->checkForm($form);
            if(true == ($checkFrom['checkForm'])){
                $this->addFlash(
                    'danger',
                    'Bitte prüfen Sie Ihr Formular'
                );
                return $this->render('Dashboard/Content Elements/Brettinghams/new.html.twig', [
                    'tbs_module' => $tbsModule,
                    'form' => $form->createView(),
                    'checkFrom' => $checkFrom,
                ]);
            }

            /** @var UploadedFile $images */
            $images = $form->get('moduleImages')->getData();

            if ($images) {
                foreach ($images as $img){
                    $imageFileName = $fileUploader->upload($img);
                    $file = new \App\Entity\File();
                    $file->setImageName($imageFileName);
                    $file->setModule($tbsModule);
                    $file->setImageSize($img->getSize());
                    $entityManager->persist($file);
                }
            }
            $tbsModule->setAuthor($this->loggedInUser);
            $entityManager->persist($tbsModule);
            $entityManager->flush();
            $this->addFlash('success',
                'Modul erfolgreich hinzugefügt'
            );
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

            $checkFrom = $this->checkForm($form);
            if(true == ($checkFrom['checkForm'])){
                $this->addFlash(
                    'danger',
                    'Bitte prüfen Sie Ihr Formular'
                );
                return $this->render('Dashboard/Content Elements/Brettinghams/new.html.twig', [
                    'tbs_module' => $tbsModule,
                    'form' => $form->createView(),
                    'checkFrom' => $checkFrom,
                ]);
            }
            
            /** @var UploadedFile $images */
            $images = $form->get('moduleImages')->getData();

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
            $tbsModule->setAuthor($this->loggedInUser);
            $this->getDoctrine()->getManager()->flush();
            $this->addFlash('success',
                'Modul erfolgreich hinzugefügt'
            );
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
        $files = $this->fileRepository->findBy([
            'module' => $tbsModule->getId()
        ]);

        return $this->render('Dashboard/Content Elements/Brettinghams/show.html.twig', [
            'tbs_module' => $tbsModule,
            'images' => $files,
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
            $files = $this->fileRepository->findBy([
                    'module' => $tbsModule->getId()
            ]);
            if(!empty($files)){
                foreach ($files as $file){
                    $filesystem->remove(['unlink',$currentDirPath.'/uploads/images/'.$file->getImageName()]);
                }
            }
            $this->addFlash('success',
                'Modul erfolgreich entfernt'
            );
            $entityManager->remove($tbsModule);
            $entityManager->flush();
        }

        return $this->redirectToRoute('tbs_module_index');
    }

    /**
     * @param string $paramater
     * @return string
     */
    private function getSortParameter(string $paramater):string{
        $sortPos = strpos($paramater,"+");
        return strtoupper(substr($paramater,$sortPos+1));
    }


    /**
     * @param FormInterface $form
     * @return array
     */
    private function checkForm(FormInterface $form) : array{

        $result['checkForm'] = false;

        $tsConfig = $form->get('tsConfigCode')->getData();
        $typoScriptCode = $form->get('typoScriptCode')->getData();
        $ttContentCode = $form->get('ttContentCode')->getData();


        if ((null == $tsConfig) || (str_contains($tsConfig, '#Dies-ist-nur-ein-Beispiel'))  ) {
            $result['checkForm'] = true;
            $result['tsConfigCode'] = true;
            $result['error']['tsConfigCode'] = 'Error: Bitte prüfen Sie den TsConfig-Code!';
        }

        if ((null == $typoScriptCode) || (str_contains($typoScriptCode, '#Dies-ist-nur-ein-Beispiel'))  ) {
            $result['checkForm'] = true;
            $result['typoScriptCode'] = true;
            $result['error']['typoScriptCode'] = 'Error: Bitte prüfen Sie den Typoscript-Code!';
        }

        if ((null == $ttContentCode) || (str_contains($ttContentCode, '#Dies-ist-nur-ein-Beispiel'))  ) {
            $result['checkForm'] = true;
            $result['ttContentCode'] = true;
            $result['error']['ttContentCode'] = 'Error: Bitte prüfen Sie den ttContent-Code!';
        }

        return $result;
    }
}
