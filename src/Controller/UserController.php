<?php

namespace App\Controller;

use App\Entity\File;
use App\Form\UserType;
use App\Repository\EntrepriseRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Dompdf\Dompdf;
use Dompdf\Options;
use Symfony\Component\Security\Core\User\UserInterface;

#[Route('/cms/user')]
class UserController extends AbstractController
{
    #[Route('/', name: 'app_profile')]
    public function index(EntrepriseRepository $entrepriseRepository): Response
    {
        $entreprise = $entrepriseRepository->findAll();

        $user = $this->getUser();
        return $this->render('user/index.html.twig', [
            'user' => $user,
            'entreprise' => $entreprise
        ]);
    }

    #[Route('/edit', name: 'app_profile_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request): Response
    {
        $user= $this->getUser();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            if($form->get('files')->getData()){
                $this->uploadFile($form->get('files')->getData(), $user);
            }

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            $this->addFlash('success', 'Mise à jour réussie');
            return $this->redirectToRoute('app_profile');
        }

        return $this->render('user/edit_user.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/pass_modifier', name: 'pass_modifier', methods: ['GET', 'POST'])]
    public function editPass(Request $request, UserPasswordEncoderInterface $passwordEncoder): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        if($request->isMethod('POST')){

            $em = $this->getDoctrine()->getManager();

            $user = $this->getUser();

            $oldPassword = $request->request->get('oldPass');
            if (!$passwordEncoder->isPasswordValid($user, $oldPassword)){
                $this->addFlash('warning', 'Mot de passe incorrect');

                return $this->redirectToRoute('pass_modifier');
            }

            // On vérifie si les 2 mots de passe sont identiques
            if($request->request->get('password') == $request->request->get('password2')){
                $user->setPassword($passwordEncoder->encodePassword($user, $request->request->get('password')));
                $em->flush();
                $this->addFlash('success', 'Mise à jour réussie');

                return $this->redirectToRoute('app_profile');
            }else{
                $this->addFlash('error', 'Les deux mots de passe doivent être identiques');
            }
        }

        return $this->render('user/editpassword.html.twig');
    }

    #[Route('/data', name: 'user_data')]
    public function usersData(): Response
    {
        return $this->render('user/data.html.twig');
    }

    #[Route('/data_download', name: 'data_download')]
    public function usersDataDownload(): Response
    {
        $pdfOptions = new Options();
        $pdfOptions->set('defaultFont', 'Arial');
        $pdfOptions->setIsRemoteEnabled(true);

        //Instanciation de DomPdf
        $dompdf = new Dompdf($pdfOptions);
        $context = stream_context_create([
            'ssl' => [
                'verify_peer' => FALSE,
                'verify_peer_name' => FALSE,
                'allow_self_signed' => TRUE
            ]
        ]);
        $dompdf->setHttpContext($context);

        // html
        $html = $this->renderView('user/data_download.html.twig');

        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        // nom du fichier
        $fichier = 'user-data-'. $this->getUser().'.pdf';

        // Envoi du PDF au navigateur
        $dompdf->stream($fichier, [
            'Attachment' => true
        ]);

        return new Response();
    }

    /**
     * @param $file
     * @param $user
     */
    public function uploadFile($file, $user)
    {
        $image = $file;
        $fichier = md5(uniqid()) . '.' . $image->guessExtension();
        $name = $image->getClientOriginalName();
        $image->move(
            $this->getParameter('files_directory'),
            $fichier
        );
        $img = new File();
        $img->setName($fichier);
        $img->setNameFile($name);
        $user->addFiles($img);
    }
}