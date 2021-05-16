<?php

namespace App\Controller\Extension;

use App\Api\ApiGitlab;
use App\Api\ApiVersion;
use App\Entity\TbsExtension;
use App\Form\TbsExtensionType;
use App\Repository\FileRepository;
use App\Repository\TbsExtensionRepository;
use App\Repository\TbsModuleRepository;
use App\Service\LoggedInUserService;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use App\Service\FileUploader;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\VarDumper\VarDumper;

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
     * @var TbsExtensionRepository
     */
    private $tbsExtensionRepository;

    /**
     * TbsExtensionController constructor.
     * @param ApiVersion $apiVersion
     * @param LoggedInUserService $loggedInUser
     * @param FileRepository $fileRepository
     * @param TbsModuleRepository $tbsModuleRepository
     * @param TbsExtensionRepository $tbsExtensionRepository
     */
    public function __construct(ApiVersion $apiVersion, LoggedInUserService $loggedInUser, FileRepository $fileRepository, TbsModuleRepository $tbsModuleRepository, TbsExtensionRepository $tbsExtensionRepository)
    {
        $this->apiVersion = $apiVersion;
        $this->loggedInUser= $loggedInUser->getUser();
        $this->fileRepository = $fileRepository;
        $this->tbsModuleRepository = $tbsModuleRepository;
        $this->tbsExtensionRepository = $tbsExtensionRepository;
    }


    /**
     * @Route("/", name="tbs_extension_index", methods={"GET"})
     */
    public function index(Request $request,TbsExtensionRepository $tbsExtensionRepository,PaginatorInterface $paginator): Response
    {
        $result = $this->tbsExtensionRepository->findAll();

        if ($request->isMethod('GET'))  {
            $filterTitle = $request->query->get('filterTitle');
            $filterDate = $request->query->get('filterDate');
            if($filterTitle){
                $result = $this->tbsExtensionRepository->filterByOrder($this->getSortParameter($filterTitle),'title');
            }
            if($filterDate){
                $result = $this->tbsExtensionRepository->filterByOrder($this->getSortParameter($filterDate),'created');
            }
            $filterTypo3_11 = $request->query->get('filterTypo3-11-dev');
            if($filterTypo3_11){
                $result = $this->tbsExtensionRepository->filerByVersion('TYPO3 11');
            }

            $filterTypo3_10 = $request->query->get('filterTypo3-10');
            if($filterTypo3_10){
                $result = $this->tbsExtensionRepository->filerByVersion('TYPO3 10 LTS');
            }
            $filterTypo3_9 = $request->query->get('filterTypo3-9');
            if($filterTypo3_9){
                $result = $this->tbsExtensionRepository->filerByVersion('TYPO3 9 LTS');
            }

        }
        $pagination = $paginator->paginate(
            $result, /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            6 /*limit per page*/
        );

        return $this->render('Dashboard/Extension/index.html.twig', [
            'tbs_extensions' => $pagination,
            'filterTitle' => $filterTitle ?? null,
            'filterDate' => $filterDate ?? null,
            'filterTypo3_11' => $filterTypo3_11 ?? null,
            'filterTypo3_10' => $filterTypo3_10 ?? null,
            'filterTypo3_9' => $filterTypo3_9 ?? null,
        ]);
    }

    /**
     * @Route("/new", name="tbs_extension_new", methods={"GET","POST"})
     */
    public function new(Request $request,FileUploader $fileUploader): Response
    {
        $tbsExtension = new TbsExtension();
        $form = $this->createForm(TbsExtensionType::class, $tbsExtension);
        $form->handleRequest($request);
        $typo3Version = $this->apiVersion->fetchTypo3VersionInformation();
        if(null === $typo3Version){
            $apiVersionFailed = true;
        }

        if ($form->isSubmitted() && $form->isValid()) {
            $version = $request->get('versions');
            if(null === $version){
                $this->addFlash(
                    'danger',
                    'Typo3 Version Pflicht'
                );
                return $this->redirectToRoute('tbs_extension_new');
            }else{
                $tbsExtension->setTypo3Version($version);
            }

            /** @var UploadedFile $extension */
            $extensionFile = $form->get('extensionZip')->getData();
            if(null === $extensionFile){
                $this->addFlash(
                    'danger',
                    'Zip. Extension Required'
                );
                return $this->redirectToRoute('tbs_extension_new');
            }else {
                $extensionFileName = $fileUploader->upload($extensionFile,true);
                $tbsExtension->setExtensionZip($extensionFileName);
            }
            $this->addFlash('success',
                'Extension wird erfolgreich angelegt'
            );
            $tbsExtension->setAuthor($this->loggedInUser);
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
    public function edit(Request $request, TbsExtension $tbsExtension,FileUploader $fileUploader): Response
    {
        $oldExtensionFileName = $tbsExtension->getExtensionZip();
        $form = $this->createForm(TbsExtensionType::class, $tbsExtension);
        $form->handleRequest($request);
        $typo3Version = $this->apiVersion->fetchTypo3VersionInformation();
        if(null === $typo3Version){
            $apiVersionFailed = true;
        }

        $filesystem = new Filesystem();
        $currentDirPath = getcwd();

        if ($form->isSubmitted() && $form->isValid()) {

            /** @var UploadedFile $extension */
            $extensionFileNew = $form->get('extensionZip')->getData();

            // new extension
            if($oldExtensionFileName != $extensionFileNew) {
                $filesystem->remove(['unlink',$currentDirPath.'/uploads/tbs-extension/'.$oldExtensionFileName]);
                $extensionFileName = $fileUploader->upload($extensionFileNew,true);
                $tbsExtension->setExtensionZip($extensionFileName);
            }

            $this->getDoctrine()->getManager()->flush();
            return $this->redirectToRoute('tbs_extension_index');
        }

        return $this->render('Dashboard/Extension/edit.html.twig', [
            'tbs_extension' => $tbsExtension,
            'form' => $form->createView(),
            'typo3Version' => $typo3Version,
            'apiVersionFailed' => $apiVersionFailed ?? false,
        ]);
    }

    /**
     * @Route("/{id}", name="tbs_extension_delete", methods={"POST"})
     */
    public function delete(Request $request, TbsExtension $tbsExtension): Response
    {
        if ($this->isCsrfTokenValid('delete'.$tbsExtension->getId(), $request->request->get('_token'))) {

            $filesystem = new Filesystem();
            $currentDirPath = getcwd();
            $filesystem->remove(['unlink',$currentDirPath.'/uploads/tbs-extension/'.$tbsExtension->getExtensionZip()]);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($tbsExtension);
            $entityManager->flush();
        }

        return $this->redirectToRoute('tbs_extension_index');
    }

    /**
     * @param string $paramater
     * @return string
     */
    private function getSortParameter(string $paramater):string{
        $sortPos = strpos($paramater,"+");
        return strtoupper(substr($paramater,$sortPos+1));
    }
}
