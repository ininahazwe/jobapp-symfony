<?php

namespace App\Controller;

use App\Entity\File;
use App\Entity\Page;
use App\Form\PageType;
use App\Form\SearchForm;
use App\Repository\PageRepository;
use DateTime;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/cms/page')]
class PageController extends AbstractController
{
    #[Route('/', name: 'page_index', methods: ['GET', 'POST'])]
    public function index(PageRepository $pageRepository, Request $request, PaginatorInterface $paginator): Response
    {
        $data = $pageRepository->findAll();

        $pages = $paginator->paginate(
            $data,
            $request->query->getInt('page', 1),
            10
        );

        $form = $this->createForm(SearchForm::class);

        $search = $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $pages = $pageRepository->search(
                $search->get('mots')->getData()
            );
        }

        return $this->render('page/index.html.twig', [
            'pages' => $pages,
            'form' => $form->createView()
        ]);
    }

    #[Route('/new', name: 'page_new', methods: ['GET', 'POST'])]
    public function new(Request $request): Response
    {
        $page = new Page();
        $form = $this->createForm(PageType::class, $page);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            if($form->get('files')->getData()){
                $this->uploadFile($form->get('files')->getData(), $page);
            }


            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($page);
            $entityManager->flush();

            $this->addFlash('success', 'Ajout réussi');

            return $this->redirectToRoute('page_index');
        }

        return $this->render('page/new.html.twig', [
            'page' => $page,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{slug}', name: 'page_show', methods: ['GET'])]
    public function show(Page $page): Response
    {
        return $this->render('page/show.html.twig', [
            'page' => $page,
        ]);
    }

    #[Route('/{id}/edit', name: 'page_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Page $page): Response
    {
        $form = $this->createForm(PageType::class, $page);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            if($form->get('files')->getData()){
                $this->uploadFile($form->get('files')->getData(), $page);
            }
            $page->updateTimestamps();

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($page);
            $entityManager->flush();

            $this->addFlash('success', 'Mise à jour réussie');

            return $this->redirectToRoute('page_index');
        }

        return $this->render('page/edit.html.twig', [
            'page' => $page,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'page_delete', methods: ['POST'])]
    public function delete(Request $request, Page $page): Response
    {
        if ($this->isCsrfTokenValid('delete'.$page->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($page);
            $entityManager->flush();
        }

        return $this->redirectToRoute('page_index');
    }

    /**
     * @param $file
     * @param $page
     */
    public function uploadFile($file, $page)
    {
        $image = $file;
        $fichier = md5(uniqid()) . '.' . $image->guessExtension();
        $name = $image->getClientOriginalName();
        $file->move(
            $this->getParameter('files_directory'),
            $fichier
        );
        $img = new File();
        $img->setName($fichier);
        $img->setNameFile($name);
        $page->addFile($img);
    }
}
