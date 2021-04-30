<?php

namespace App\Controller\Module;

use App\Entity\ExportContentElement;
use App\Form\ExportContentElementType;
use App\Repository\ExportContentElementRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/export/module")
 */
class ExportContentElementController extends AbstractController
{
    /**
     * @Route("/", name="export_content_element_index", methods={"GET"})
     */
    public function index(ExportContentElementRepository $exportContentElementRepository): Response
    {
        return $this->render('Dashboard/Export/index.html.twig', [
            'export_content_elements' => $exportContentElementRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="export_content_element_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $exportContentElement = new ExportContentElement();
        $form = $this->createForm(ExportContentElementType::class, $exportContentElement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($exportContentElement);
            $entityManager->flush();

            return $this->redirectToRoute('export_content_element_index');
        }

        return $this->render('export_content_element/new.html.twig', [
            'export_content_element' => $exportContentElement,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="export_content_element_show", methods={"GET"})
     */
    public function show(ExportContentElement $exportContentElement): Response
    {
        return $this->render('export_content_element/show.html.twig', [
            'export_content_element' => $exportContentElement,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="export_content_element_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, ExportContentElement $exportContentElement): Response
    {
        $form = $this->createForm(ExportContentElementType::class, $exportContentElement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('export_content_element_index');
        }

        return $this->render('export_content_element/edit.html.twig', [
            'export_content_element' => $exportContentElement,
            'form' => $form->createView(),
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
}
