<?php

namespace App\Controller;

use App\Entity\Offre;
use App\Form\OffreType;
use App\Repository\OffreRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/cms/offres')]
class OffreController extends AbstractController
{
    #[Route('/', name: 'offre_index', methods: ['GET'])]
    public function index(OffreRepository $entrepriseOffreRepository): Response
    {
        return $this->render('offre/index.html.twig', [
            'offres' => $entrepriseOffreRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'offre_new', methods: ['GET', 'POST'])]
    public function new(Request $request): Response
    {
        $entrepriseOffre = new Offre();
        $form = $this->createForm(OffreType::class, $entrepriseOffre);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($entrepriseOffre);
            $entityManager->flush();

            $this->addFlash('success', 'Ajout réussi');

            return $this->redirectToRoute('offre_index');
        }

        return $this->render('offre/new.html.twig', [
            'offre' => $entrepriseOffre,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'offre_show', methods: ['GET'])]
    public function show(Offre $entrepriseOffre): Response
    {
        return $this->render('offre/show.html.twig', [
            'offre' => $entrepriseOffre,
        ]);
    }

    #[Route('/{id}/edit', name: 'offre_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Offre $entrepriseOffre): Response
    {
        $form = $this->createForm(OffreType::class, $entrepriseOffre);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            $this->addFlash('success', 'Mise à jour réussie');

            return $this->redirectToRoute('offre_index');
        }

        return $this->render('offre/edit.html.twig', [
            'offre' => $entrepriseOffre,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'offre_delete', methods: ['POST'])]
    public function delete(Request $request, Offre $entrepriseOffre): Response
    {
        if ($this->isCsrfTokenValid('delete'.$entrepriseOffre->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($entrepriseOffre);
            $entityManager->flush();
        }

        return $this->redirectToRoute('offre_index');
    }
}
