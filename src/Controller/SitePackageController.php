<?php

namespace App\Controller;

use App\Api\ApiProvider;
use App\Entity\SitePackage;
use App\Entity\User;
use App\Form\SitePackageType;
use App\Repository\SitePackageRepository;
use App\Service\LoggedInUserService;
use Symfony\Bundle\DebugBundle\DebugBundle;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\VarDumper\VarDumper;
use Symfony\Contracts\HttpClient\HttpClientInterface;

/**
 * @Route("/admin/site-package")
 */
class SitePackageController extends AbstractController
{

    /**
     * @var ApiProvider
     */
    private $apiProvider;

    /**
     * @var HttpClientInterface
     */
    private $client;

    /**
     * @var LoggedInUserService
     */
    private $loggedInUser;

    const COMPANY = "THE BRETTINGHAMS";
    const HOMEPAGE = "https://www.brettingham.de/";

    const SITE_PACKAGE_APi_URL = "https://www.sitepackagebuilder.com/api/v1/sitepackage/";

    /**
     * SitePackageController constructor.
     * @param HttpClientInterface $client
     * @param LoggedInUserService $loggedInUser
     * @param ApiProvider $apiProvider
     */
    public function __construct(HttpClientInterface $client, LoggedInUserService $loggedInUser, ApiProvider $apiProvider)
    {
        $this->client = $client;
        $this->loggedInUser= $loggedInUser->getUser();
        $this->apiProvider= $apiProvider;
    }

    /**
     * @Route("/", name="site_package_index", methods={"GET"})
     */
    public function index(SitePackageRepository $sitePackageRepository): Response
    {
        return $this->render('Dashboard/Site Package/index.html.twig', [
            'site_packages' => $sitePackageRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="site_package_new", methods={"GET","POST"})
     */
    public function new(Request $request ): Response
    {
        $sitePackage = new SitePackage();
        $repository = $this->getDoctrine()->getRepository(SitePackage::class);
        $form = $this->createForm(SitePackageType::class, $sitePackage);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $sitePackageExist = $repository->findOneBy([
                'typo3Version' => $sitePackage->getTypo3Version(),
                'basePackage' => $sitePackage->getBasePackage(),
                'client' => $sitePackage->getClient(),
            ]);
            if ($sitePackageExist) {
                $this->addFlash('info', sprintf(
                    'Eine Extension mit der folgenden Konfiguration bereits generiert ! Id %s',
                     $sitePackageExist->getId()
                 ));
                return $this->redirectToRoute('site_package_index');
            }

            $entityManager = $this->getDoctrine()->getManager();
            $bodyInformation = $this->generateJson($sitePackage,$this->loggedInUser);

            if((null === $bodyInformation) || (null === $this->loggedInUser)){
                $this->addFlash(
                    'danger',
                    'Fehler beim Handling von json-Daten'
                );
                return $this->redirectToRoute('site_package_new');
            }
            $response = $this->apiProvider->fetchSitePackageInformation($this->client,SitePackageController::SITE_PACKAGE_APi_URL,'POST',$bodyInformation,$sitePackage->getTitle());
            if ((strpos($response, 'Error') == false) && (null != $response)){
                $sitePackage->setAuthor($this->loggedInUser->getEmail());
                $sitePackage->setAuthorCompany(self::COMPANY);
                $sitePackage->setAuthorEmail($this->loggedInUser->getEmail());
                $sitePackage->setUser($this->loggedInUser);
                $sitePackage->setAuthorHomePage(self::HOMEPAGE);
                $sitePackage->setIsShown(true);
                $sitePackage->setPath($response);

                $entityManager->persist($sitePackage);
                $entityManager->flush();

                $this->addFlash('success', sprintf(
                    'Extension wird erfolgreich generiert! Herunterladen! Id = %s',
                    $sitePackage->getId()
                ));
                return $this->redirectToRoute('site_package_index');
            }else{
                if(false ===  $response )
                    $response = "Fehler beim Generieren der Extension";
                $this->addFlash(
                    'danger',
                    $response
                );
                return $this->redirectToRoute('site_package_new');
            }
        }

        return $this->render('Dashboard/Site Package/new.html.twig', [
            'site_package' => $sitePackage,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="site_package_delete", methods={"POST"})
     */
    public function delete(Request $request, SitePackage $sitePackage): Response
    {
        if ($this->isCsrfTokenValid('delete'.$sitePackage->getId(), $request->request->get('_token'))) {
            $fileName = basename($sitePackage->getPath());
            // remove file
            $filesystem = new Filesystem();
            $currentDirPath = getcwd();
            $filesystem->remove(['symlink', $currentDirPath.$sitePackage->getPath(),$fileName]);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($sitePackage);
            $entityManager->flush();
        }

        return $this->redirectToRoute('site_package_index');
    }


    /**
     * @param SitePackage $sitePackage
     * @param UserInterface $user
     * @return String|null
     */
    public function generateJson(SitePackage  $sitePackage, UserInterface $user): ?String
    {
        $encoders = [new JsonEncoder()];
        $normalizers = [new ObjectNormalizer()];
        $serializer = new Serializer($normalizers, $encoders);

        $parameters['typo3_version'] = strval($sitePackage->getTypo3Version());
        $parameters['base_package'] = $sitePackage->getBasePackage();
        $parameters['title'] = $sitePackage->getTitle();
        $parameters['description'] = $sitePackage->getDescription()  ?? '';
        $parameters['repository_url'] = $sitePackage->getRepositoryUrl()  ?? '';
        $parameters['author']['name'] = $user->getEmail();
        $parameters['author']['email'] = $user->getEmail();
        $parameters['author']['company'] = SitePackageController::COMPANY;
        $parameters['author']['homepage'] =  SitePackageController::HOMEPAGE;

        return $serializer->serialize($parameters, 'json') ?? null ;
    }
}
