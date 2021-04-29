<?php

namespace App\Controller;

use App\Entity\ContentElements;
use App\Entity\SitePackage;
use App\Form\ContentElementsType;
use App\Repository\ContentElementsRepository;
use App\Service\LoggedInUserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\VarDumper\VarDumper;

/**
 * @Route("/admin/content-elements")
 */
class ContentElementsController extends AbstractController
{

    /**
     * @var LoggedInUserService
     */
    private $loggedInUser;

    /**
     * ContentElementsController constructor.
     * @param LoggedInUserService $loggedInUser
     */
    public function __construct(LoggedInUserService $loggedInUser)
    {
        $this->loggedInUser= $loggedInUser->getUser();
    }

    /**
     * @Route("/", name="content_elements_index", methods={"GET"})
     */
    public function index(ContentElementsRepository $contentElementsRepository): Response
    {
        return $this->render('Dashboard/Content Elements/index.html.twig', [
            'content_elements' => $contentElementsRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="content_elements_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $contentElement = new ContentElements();
        $repository = $this->getDoctrine()->getRepository(ContentElements::class);
        $form = $this->createForm(ContentElementsType::class, $contentElement);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $entityManager = $this->getDoctrine()->getManager();

            $contentElementExist = $repository->findOneBy([
                'elementKey' => $contentElement->getElementKey(),
            ]);
            if ($contentElementExist) {
                $this->addFlash('danger', sprintf(
                    'elementKey existiert schon ! Id %s',
                    $contentElementExist->getId()
                ));

                return $this->redirectToRoute('content_elements_new');
            }

            if((null === $contentElement->getFormData()) || (null === $this->loggedInUser)){
                $this->addFlash('danger', 'Fehler beim Handling von json-Daten (FormBuilder)');
                return $this->redirectToRoute('content_elements_new');
            }else{
                $contentElement->setUser($this->loggedInUser);
                $entityManager->persist($contentElement);
                $entityManager->flush();

                $this->addFlash(
                    'success',
                    'CE wird erfolgreich generiert'
                );
                return $this->redirectToRoute('content_elements_index');
            }
        }

        return $this->render('Dashboard/Content Elements/new.html.twig', [
            'content_element' => $contentElement,
            'form' => $form->createView(),
        ]);
    }


    /**
     * @Route("/{id}/edit", name="content_elements_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, ContentElements $contentElement): Response
    {
        $repository = $this->getDoctrine()->getRepository(ContentElements::class);
        $form = $this->createForm(ContentElementsType::class, $contentElement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $contentElementExist = $repository->findOneBy([
                'elementKey' => $contentElement->getElementKey(),
            ]);
            if (($contentElementExist) && ($contentElement->getId() != $contentElementExist->getId())) {
                $this->addFlash('danger', sprintf(
                    'elementKey existiert schon ! Id %s',
                    $contentElementExist->getId()
                ));

                return $this->redirectToRoute('content_elements_edit');
            }

            if(("[]" === $contentElement->getFormData()) || (null === $this->loggedInUser)){
                $this->addFlash('danger', 'Fehler beim Handling von json-Daten (FormBuilder)');
                return $this->redirectToRoute('content_elements_edit');
            }else{
                $this->getDoctrine()->getManager()->flush();
                return $this->redirectToRoute('content_elements_index');
            }
        }

        return $this->render('Dashboard/Content Elements/edit.html.twig', [
            'content_element' => $contentElement,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="content_elements_delete", methods={"POST"})
     */
    public function delete(Request $request, ContentElements $contentElement): Response
    {
        if ($this->isCsrfTokenValid('delete'.$contentElement->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($contentElement);
            $entityManager->flush();
        }

        return $this->redirectToRoute('content_elements_index');
    }
}
