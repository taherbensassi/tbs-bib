<?php

namespace App\Controller;

use App\Entity\SitePackage;
use App\Form\SitePackageType;
use App\Repository\SitePackageRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/site-package")
 */
class SitePackageController extends AbstractController
{
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
    public function new(Request $request): Response
    {
        $sitePackage = new SitePackage();
        $form = $this->createForm(SitePackageType::class, $sitePackage);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($sitePackage);
            $entityManager->flush();

            return $this->redirectToRoute('site_package_index');
        }

        return $this->render('Dashboard/Site Package/new.html.twig', [
            'site_package' => $sitePackage,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="site_package_show", methods={"GET"})
     */
    public function show(SitePackage $sitePackage): Response
    {
        return $this->render('site_package/show.html.twig', [
            'site_package' => $sitePackage,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="site_package_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, SitePackage $sitePackage): Response
    {
        $form = $this->createForm(SitePackageType::class, $sitePackage);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('site_package_index');
        }

        return $this->render('site_package/edit.html.twig', [
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
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($sitePackage);
            $entityManager->flush();
        }

        return $this->redirectToRoute('site_package_index');
    }
}
