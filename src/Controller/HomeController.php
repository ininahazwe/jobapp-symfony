<?php

namespace App\Controller;

use App\Entity\Menu;
use App\Entity\Page;
use App\Repository\AnnonceRepository;
use App\Repository\MenuRepository;
use App\Repository\ModeleOffreCommercialeRepository;
use App\Repository\OffreRepository;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/')]
class HomeController extends AbstractController
{
    #[Route('/', name:'app_home')]
    public function index(AnnonceRepository $annoncesRepo, Request $request, ModeleOffreCommercialeRepository $modeleOffreCommercialeRepository): Response
    {
        $annonces = $annoncesRepo->findBy(['isActive' => true], ['createdAt' => 'desc'], 5);
        $offres = $modeleOffreCommercialeRepository->findAll();

        return $this->render('home/index.html.twig', [
            'annonces' => $annonces,
            'modeles' => $offres,
        ]);
    }
    #[Route('/view/menus', name:'app_view_menus')]
    public function menuIndex(MenuRepository $menuRepository): Response
    {
        $menus = $menuRepository->getAllMenus();
        return $this->render('layouts/_header.html.twig', [
            'menus' => $menus,
            'menuRepository' => $menuRepository
        ]);
    }

    #[Route('/view/footer', name:'app_view_footer')]
    public function footerIndex(MenuRepository $menuRepository): Response
    {
        $footers = $menuRepository->getAllFooters();
        return $this->render('layouts/_footer.html.twig', [
            'footers' => $footers,
            'contenu' => $menuRepository->findOneBy(['type' => Menu::TYPE_MENU_CONTENU]),
            'presentationRs' => $menuRepository->findOneBy(['type' => Menu::TYPE_MENU_PRESENTATION_RS]),
            'twitter' => $menuRepository->findOneBy(['type' => Menu::TYPE_MENU_TWITTER]),
            'facebook' => $menuRepository->findOneBy(['type' => Menu::TYPE_MENU_FACEBOOK]),
            'instagram' => $menuRepository->findOneBy(['type' => Menu::TYPE_MENU_INSTAGRAM]),
            'linkedin' => $menuRepository->findOneBy(['type' => Menu::TYPE_MENU_LINKEDIN]),
            'skype' => $menuRepository->findOneBy(['type' => Menu::TYPE_MENU_SKYPE]),
            'youtube' => $menuRepository->findOneBy(['type' => Menu::TYPE_MENU_YOUTUBE]),
            'menuRepository' => $menuRepository
        ]);
    }


    #[Route('/{slug}', name:'page_sss')]
    public function page($slug): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $page = $entityManager->getRepository(Page::class)->findOneBy(['slug' => $slug]);
        if(!$page){
            return $this->redirect($this->generateUrl('app_home'));
        }
        return $this->render('page/show.html.twig', [
            'page' => $page
        ]);
    }
}
