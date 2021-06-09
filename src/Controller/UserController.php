<?php

namespace App\Controller;

use App\Entity\Entreprise;
use App\Repository\EntrepriseRecruteurRepository;
use App\Repository\EntrepriseRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Dompdf\Dompdf;
use Dompdf\Options;

#[Route('/user')]
class UserController extends AbstractController
{
    #[Route('/', name: 'app_profile')]
    public function index(EntrepriseRepository $entrepriseRepository): Response
    {
        $entreprise = $entrepriseRepository->findAll();

        $this->denyAccessUnlessGranted('ROLE_CANDIDAT');
        $user = $this->getUser();
        return $this->render('user/index.html.twig', [
            'user' => $user,
            'entreprise' => $entreprise
        ]);
    }

    #[Route('/pass_modifier', name: 'pass_modifier', methods: ['GET', 'POST'])]
    public function editPass(Request $request, UserPasswordEncoderInterface $passwordEncoder): Response
    {
        if($request->isMethod('POST')){

            $em = $this->getDoctrine()->getManager();

            $user = $this->getUser();

            // On vérifie si les 2 mots de passe sont identiques
            if($request->request->get('password') == $request->request->get('password2')){
                $user->setPassword($passwordEncoder->encodePassword($user, $request->request->get('password')));
                $em->flush();
                $this->addFlash('message', 'Mot de passe mis à jour avec succès');

                return $this->redirectToRoute('app_profile');
            }else{
                $this->addFlash('error', 'Les deux mots de passe ne sont pas identiques');
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
}