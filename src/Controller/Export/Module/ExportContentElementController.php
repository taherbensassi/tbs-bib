<?php

namespace App\Controller\Export\Module;

use App\Controller\Export\Implementations\ZipServiceInterfaceImpl;
use App\Controller\Export\Interfaces\ExportServiceInterface;
use App\Controller\Export\Interfaces\ZipServiceInterface;
use App\Entity\ExportContentElement;
use App\Form\ExportContentElementType;
use App\Repository\ContentElementsRepository;
use App\Repository\ExportContentElementRepository;
use App\Repository\TbsModuleRepository;
use App\Service\LoggedInUserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\VarDumper\VarDumper;

/**
 * @Route("/admin/export-module")
 */
class ExportContentElementController extends AbstractController
{

    /**
     * @var LoggedInUserService
     */
    private $loggedInUser;

    /**
     * @var ContentElementsRepository
     */
    private $contentElementRepository;

    /**
     * @var TbsModuleRepository
     */
    private $tbsModuleRepository;

    /**
     * @var ExportServiceInterface
     */
    private $exportServiceInterface;

    /**
     * @var ExportContentElementRepository
     */
    private $exportContentElementRepository;

    /**
     * @var ZipServiceInterface
     */
    private $zipServiceInterface;

    /**
     * ExportContentElementController constructor.
     * @param ContentElementsRepository $contentElementRepository
     * @param TbsModuleRepository $tbsModuleRepository
     * @param ExportServiceInterface $exportServiceInterface
     * @param ZipServiceInterface $zipServiceInterface
     * @param LoggedInUserService $loggedInUser
     */
    public function __construct(ContentElementsRepository $contentElementRepository, TbsModuleRepository $tbsModuleRepository, ExportServiceInterface $exportServiceInterface, ZipServiceInterface $zipServiceInterface,LoggedInUserService $loggedInUser,ExportContentElementRepository $exportContentElementRepository)
    {
        $this->contentElementRepository = $contentElementRepository;
        $this->tbsModuleRepository = $tbsModuleRepository;
        $this->exportServiceInterface = $exportServiceInterface;
        $this->zipServiceInterface = $zipServiceInterface;
        $this->loggedInUser= $loggedInUser->getUser();
        $this->exportContentElementRepository= $exportContentElementRepository;
    }

    /**
     * @Route("/", name="export_content_element_index", methods={"GET"})
     */
    public function index(ExportContentElementRepository $exportContentElementRepository): Response
    {
        $tbsContentElement = $this->tbsModuleRepository->findBy([
            'status' => true,
        ]);
        return $this->render('Dashboard/Content Elements/Export/index.html.twig', [
            'export_content_elements' => $exportContentElementRepository->findAll(),
            'tbsContentElement' => $tbsContentElement,
        ]);
    }

    /**
     * @todo make it work in frontend
     * @Route("/regenerate_export", name="regenerate_export", methods={"POST"})
     */
    public function regenerateExport(Request $request): Response
    {
        if (!$request->isXmlHttpRequest()) {
            return new JsonResponse(array(
                'status' => 'Error',
                'message' => 'Error'),
                400);
        }
        if ($request->isXmlHttpRequest()) {
            // Get data from ajax
            $exportedModule = $request->get('id');

            $id = intval($exportedModule);
            if ($id <= 0)
            {
                return new JsonResponse(array(
                    'status' => 'Error',
                    'message' => 'Error'),
                    400);
            }
            $exportRepo = $this->exportContentElementRepository->find($id);
            if (null === $exportRepo)
            {
                // Looks like something went wrong
                return new JsonResponse(array(
                    'status' => 'Error',
                    'message' => 'Error'),
                    400);
            }

            //-- initialize extension
            $this->zipServiceInterface->initialize();
            //-- handle Export
            $export = $this->handelExport($exportRepo->getTbsModule());

            //-- export done
            if(true === $export){
                //create zip
                $this->zipServiceInterface->createZip();
                //-- unset extension
                $this->zipServiceInterface->unsetTbsExtension();
                //- downloaded extension
                return $this->zipServiceInterface->downloadExtension();
            }else{
                $this->zipServiceInterface->unsetTbsExtension();
                return new JsonResponse(array(
                    'status' => 'Error',
                    'message' => 'Error'),
                    400);
            }
        }
        return new JsonResponse(array(
            'status' => 'Error',
            'message' => 'Error'),
            400);
    }

    /**
     * @Route("/new", name="export_content_element_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $exportContentElement = new ExportContentElement();
        $form = $this->createForm(ExportContentElementType::class, $exportContentElement);
        $form->handleRequest($request);
        $contentElement = $this->contentElementRepository->findAll();
        $tbsContentElement = $this->tbsModuleRepository->findBy([
                'status' => true,
        ]);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            //-- get selected Modules
            $selectedModules = $request->get('tbsModule');
            if(null == $selectedModules){
                $this->addFlash(
                    'danger',
                    'Sie müssen mindestens ein Modul auswählen'
                );
                return $this->redirectToRoute('export_content_element_new');
            }

            //-- initialize extension
            $this->zipServiceInterface->initialize();

            //-- handle Export
            $export = $this->handelExport($selectedModules);

            //-- export done
            if(true === $export){
                $exportContentElement->setStatus(0);
                $exportContentElement->setVendorName('TBS');
                $exportContentElement->setUser($this->loggedInUser);
                $exportContentElement->setExtensionName("tbs_content_elements");
                $exportContentElement->setTbsModule($selectedModules);
                $entityManager->persist($exportContentElement);
                $entityManager->flush();

                //create zip
                $this->zipServiceInterface->createZip();
                //-- unset extension
                $this->zipServiceInterface->unsetTbsExtension();
                //- downloaded extension
                return $this->zipServiceInterface->downloadExtension();
            }else{
                $unset = $this->zipServiceInterface->unsetTbsExtension();
                if(true === $unset){
                    return $this->render('Dashboard/Content Elements/Export/new.html.twig', [
                        'export_content_element' => $exportContentElement,
                        'form' => $form->createView(),
                        'customContentElement' => $contentElement,
                        'tbsContentElement' => $tbsContentElement,
                        'Errors' => $export,
                    ]);
                }else{
                    return $this->render('Dashboard/Content Elements/Export/new.html.twig', [
                        'export_content_element' => $exportContentElement,
                        'form' => $form->createView(),
                        'customContentElement' => $contentElement,
                        'tbsContentElement' => $tbsContentElement,
                        'Errors' => $export,
                        'unset' => $unset,
                    ]);
                }
            }
        }
        return $this->render('Dashboard/Content Elements/Export/new.html.twig', [
            'export_content_element' => $exportContentElement,
            'form' => $form->createView(),
            'customContentElement' => $contentElement,
            'tbsContentElement' => $tbsContentElement,
        ]);
    }



    /**
     * @Route("/{id}", name="export_content_element_delete", methods={"POST"})
     */
    public function delete(Request $request, ExportContentElement $exportContentElement): Response
    {
        if ($this->isCsrfTokenValid('delete'.$exportContentElement->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($exportContentElement);
            $entityManager->flush();
        }

        return $this->redirectToRoute('export_content_element_index');
    }


    /**
     * @param array $selectedModules
     * @return array|bool
     */
    private function handelExport(array $selectedModules) {
        //-- loop over all modules
        foreach ($selectedModules as $key => $selectedModule){

            //-- get Module
            $module = $this->tbsModuleRepository->find($selectedModule);

            $tsConfig = $this->exportServiceInterface->exportTsConfig($module->getTsConfigCode(),$module->getModuleKey());
            if(false == $tsConfig){
                $result['error']['exportTsConfigCode'] = 'Error Module: '.$module->getModuleKey().': Problem beim Exportieren von tsconfig !';
            }

            $typoScript = $this->exportServiceInterface->exportTypoScript($module->getTypoScriptCode(),$module->getModuleKey());
            if(false == $typoScript){
                $result['error']['exportTypoScriptCode'] = 'Error Module: '.$module->getModuleKey().': Problem beim Exportieren von TypoScript!';
            }

            $ttContent = $this->exportServiceInterface->exportTtContent($module->getTtContentCode(),$module->getModuleKey(),1,"");
            if(false == $ttContent){
                $result['error']['exportTtContentCode'] = 'Error Module: '.$module->getModuleKey().': Problem beim Exportieren von TtContentCode!';
            }

            if (null != $module->getSqlOverrideCode()){
                $sqlOverride = $this->exportServiceInterface->exportSql($module->getSqlOverrideCode(),$module->getModuleKey(),1);
                if(false == $sqlOverride){
                    $result['error']['SqlOverrideCode'] = 'Error Module: '.$module->getModuleKey().': Problem beim Exportieren von SqlOverrideCode!';
                }
            }

            if (null != $module->getSqlNewTableCode()){
                $sqlNewTable = $this->exportServiceInterface->exportSql($module->getSqlNewTableCode(),$module->getModuleKey(),2);
                if(false == $sqlNewTable){
                    $result['error']['SqlNewTableCode'] = 'Error Module: '.$module->getModuleKey().': Problem beim Exportieren von SqlNewTableCode!';
                }
            }

            if (null != $module->getBackendPreviewCode()){
                $backendPreview = $this->exportServiceInterface->exportHtml($module->getBackendPreviewCode(),$module->getModuleKey(),$module->getTypoScriptCode(),1);
                if(false == $backendPreview){
                    $result['error']['BackendPreviewCode'] = 'Error Module: '.$module->getModuleKey().': Problem beim Exportieren von BackendPreviewCode!';
                }
            }

            if (null != $module->getHtmlCode()) {
                $frontendPreview = $this->exportServiceInterface->exportHtml($module->getHtmlCode(), $module->getModuleKey(), $module->getTypoScriptCode(), 2);
                if (false == $frontendPreview) {
                    $result['error']['HtmlCode'] = 'Error  Module: '.$module->getModuleKey().': Problem beim Exportieren von HtmlFrontendCode!';
                }
            }

            if (null != $module->getLocalLangCode()) {
                $xmlEnLanguage = $this->exportServiceInterface->exportXml($module->getLocalLangCode(), $module->getModuleKey(), 1);
                if (false == $xmlEnLanguage) {
                    $result['error']['LocalLangCode'] = 'Error Module:'.$module->getModuleKey().': Problem beim Exportieren von LocalLangCode!';
                }
            }

            if (null != $module->getDeLangeCode()){
                $xmlDeLanguage = $this->exportServiceInterface->exportXml($module->getDeLangeCode(),$module->getModuleKey(),2);
                if(false == $xmlDeLanguage){
                    $result['error']['DeLanguage'] = 'Error Module:'.$module->getModuleKey().': Problem beim Exportieren von DeLanguage!';
                }
            }

            if (null != $module->getTtContentNewCode()){
                $ttContent = $this->exportServiceInterface->exportTtContent($module->getTtContentNewCode(),$module->getModuleKey(),2,$module->getTtContentTableName());
                if(false == $ttContent){
                    $result['error']['exportTtContentNewCode'] = 'Error Module: '.$module->getModuleKey().': Problem beim Exportieren von TtContentNewCode!';
                }
            }
        }

        // update icons
        $icons = $this->exportServiceInterface->generateModuleIcons($selectedModules);
        if(true !== $icons){
            $result['error']['icons'] = 'Error : Problem beim Exportieren von Icons!';
        }

        // update preview
        $icons = $this->exportServiceInterface->generateModuleBackendPreview($selectedModules);
        if(true !== $icons){
            $result['error']['moduleBackendPreview'] = 'Error : Problem beim Exportieren von moduleBackendPreview!';
        }

        return $result ?? true;
    }




}
