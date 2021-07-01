<?php

namespace App\Controller;

use App\Entity\ModeleOffreCommerciale;
use App\Form\ModeleOffreCommercialeType;
use App\Repository\ModeleOffreCommercialeRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/cms/modele-offre-commerciale')]
class ModeleOffreCommercialeController extends AbstractController
{
    #[Route('/', name: 'modele_offre_commerciale_index', methods: ['GET'])]
    public function index(Request $request, ModeleOffreCommercialeRepository $modeleOffreCommercialeRepository, PaginatorInterface $paginator): Response
    {
        $data = $modeleOffreCommercialeRepository->findAll();
        $modeles = $paginator->paginate(
            $data,
            $request->query->getInt('page', 1),
            4
        );
        return $this->render('modele_offre_commerciale/index.html.twig', [
            'modele_offre_commerciales' => $modeles,
        ]);
    }

    #[Route('/new', name: 'modele_offre_commerciale_new', methods: ['GET', 'POST'])]
    public function new(Request $request): Response
    {
        $modeleOffreCommerciale = new ModeleOffreCommerciale();
        $form = $this->createForm(ModeleOffreCommercialeType::class, $modeleOffreCommerciale);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($modeleOffreCommerciale);
            $entityManager->flush();

            $this->addFlash('success', 'Ajout réussi');

            return $this->redirectToRoute('modele_offre_commerciale_index');
        }

        return $this->render('modele_offre_commerciale/new.html.twig', [
            'modele_offre_commerciale' => $modeleOffreCommerciale,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'modele_offre_commerciale_show', methods: ['GET'])]
    public function show(ModeleOffreCommerciale $modeleOffreCommerciale): Response
    {
        return $this->render('modele_offre_commerciale/show.html.twig', [
            'modele_offre_commerciale' => $modeleOffreCommerciale,
        ]);
    }

    #[Route('/{id}/edit', name: 'modele_offre_commerciale_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, ModeleOffreCommerciale $modeleOffreCommerciale): Response
    {
        $form = $this->createForm(ModeleOffreCommercialeType::class, $modeleOffreCommerciale);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            $this->addFlash('success', 'Mise à jour réussie');

            return $this->redirectToRoute('modele_offre_commerciale_index');
        }

        return $this->render('modele_offre_commerciale/edit.html.twig', [
            'modele_offre_commerciale' => $modeleOffreCommerciale,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'modele_offre_commerciale_delete', methods: ['POST'])]
    public function delete(Request $request, ModeleOffreCommerciale $modeleOffreCommerciale): Response
    {
        if ($this->isCsrfTokenValid('delete'.$modeleOffreCommerciale->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($modeleOffreCommerciale);
            $entityManager->flush();
        }

        return $this->redirectToRoute('modele_offre_commerciale_index');
    }
}
