<?php

namespace App\Controller;

use App\Entity\Typo3Project;
use App\Form\Typo3ProjectType;
use App\Repository\Typo3ProjectRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/typo3/project")
 */
class Typo3ProjectController extends AbstractController
{
    /**
     * @Route("/", name="typo3_project_index", methods={"GET"})
     */
    public function index(Typo3ProjectRepository $typo3ProjectRepository): Response
    {
        return $this->render('typo3_project/index.html.twig', [
            'typo3_projects' => $typo3ProjectRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="typo3_project_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $typo3Project = new Typo3Project();
        $form = $this->createForm(Typo3ProjectType::class, $typo3Project);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($typo3Project);
            $entityManager->flush();

            return $this->redirectToRoute('typo3_project_index');
        }

        return $this->render('typo3_project/new.html.twig', [
            'typo3_project' => $typo3Project,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="typo3_project_show", methods={"GET"})
     */
    public function show(Typo3Project $typo3Project): Response
    {
        return $this->render('typo3_project/show.html.twig', [
            'typo3_project' => $typo3Project,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="typo3_project_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Typo3Project $typo3Project): Response
    {
        $form = $this->createForm(Typo3ProjectType::class, $typo3Project);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('typo3_project_index');
        }

        return $this->render('typo3_project/edit.html.twig', [
            'typo3_project' => $typo3Project,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="typo3_project_delete", methods={"POST"})
     */
    public function delete(Request $request, Typo3Project $typo3Project): Response
    {
        if ($this->isCsrfTokenValid('delete'.$typo3Project->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($typo3Project);
            $entityManager->flush();
        }

        return $this->redirectToRoute('typo3_project_index');
    }
}
