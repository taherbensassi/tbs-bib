<?php

namespace App\Controller;

use App\Entity\ContentElements;
use App\Form\ContentElementsType;
use App\Repository\ContentElementsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/content-elements")
 */
class ContentElementsController extends AbstractController
{
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
        $form = $this->createForm(ContentElementsType::class, $contentElement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($contentElement);
            $entityManager->flush();

            return $this->redirectToRoute('content_elements_index');
        }

        return $this->render('Dashboard/Content Elements/new.html.twig', [
            'content_element' => $contentElement,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="content_elements_show", methods={"GET"})
     */
    public function show(ContentElements $contentElement): Response
    {
        return $this->render('content_elements/show.html.twig', [
            'content_element' => $contentElement,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="content_elements_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, ContentElements $contentElement): Response
    {
        $form = $this->createForm(ContentElementsType::class, $contentElement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('content_elements_index');
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
