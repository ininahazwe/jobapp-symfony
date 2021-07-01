<?php

namespace App\Controller;

use App\Entity\Entreprise;
use App\Entity\File;
use App\Entity\Offre;
use App\Entity\User;
use App\Form\EntrepriseType;
use App\Form\OffreType;
use App\Form\UserType;
use App\Repository\EntrepriseRepository;
use App\Repository\FactureRepository;
use App\Repository\ModeleOffreCommercialeRepository;
use App\Repository\UserRepository;
use Exception;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

#[Route('/cms/entreprise')]
class EntrepriseController extends AbstractController
{
    #[Route('/', name: 'entreprise_index', methods: ['GET'])]
    public function index(Request $request, EntrepriseRepository $entrepriseRepository, PaginatorInterface $paginator): Response
    {
        $data = $entrepriseRepository->findAllEntreprise($this->getUser());

        $entreprises = $paginator->paginate(
            $data,
            $request->query->getInt('page', 1),
            10
        );

        if($this->getUser()->isSuperRecruteur()){
            if(count($entreprises) == 1 ){
                $entreprise = $entreprises[0];
                return $this->redirectToRoute('entreprise_show', ['slug' => $entreprise->getSlug()] );
            }
        }

        return $this->render('entreprise/index.html.twig', [
            'entreprises' => $entreprises,
        ]);
    }

    /**
     * @throws Exception
     */
    #[Route('/new', name: 'entreprise_new', methods: ['GET', 'POST'])]
    public function new(Request $request, ModeleOffreCommercialeRepository $modeleOffreCommercialeRepository, EntrepriseRepository $entrepriseRepository): Response
    {
        $entreprise = new Entreprise();
        $form = $this->createForm(EntrepriseType::class, $entreprise);
        $form->handleRequest($request);
        $ref = $entrepriseRepository->genererRef();

        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('logo')->getData()){
                $this->uploadFile($form->get('logo')->getData(), $entreprise);
            }
            $entreprise->setRefClient($ref);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($entreprise);
            $entityManager->flush();

            $modele = $modeleOffreCommercialeRepository->findOneBy(['prix' => '0']);
            $this->saveOffreModele($entreprise->getId(), $modele->getId());

            $this->addFlash('success', 'Ajout réussi');

            return $this->redirectToRoute('entreprise_recruteurs', ['id' => $entreprise->getId()]);
        }

        return $this->render('entreprise/new.html.twig', [
            'entreprise' => $entreprise,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{slug}', name: 'entreprise_show', methods: ['GET'])]
    public function show($slug, EntrepriseRepository $entrepriseRepository): Response
    {
        $entreprise = $entrepriseRepository->findOneBy(['slug' => $slug]);
        return $this->render('entreprise/show.html.twig', [
            'entreprise' => $entreprise,
        ]);
    }

    #[Route('/{id}/offres', name: 'entreprise_offres_commerciales', methods: ['GET', 'POST'])]
    public function offreCommercialeShow(Request $request, Entreprise $entreprise, ModeleOffreCommercialeRepository $modeleOffreCommercialeRepository): Response
    {
        $offre = new Offre();

        $form = $this->createForm(OffreType::class, $offre);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($offre);
            $entityManager->flush();

            return $this->redirect($this->generateUrl('entreprise_offres_commerciales'));
        }

        $modeles = $modeleOffreCommercialeRepository->findAll();
        return $this->render('entreprise/offres_commerciales.html.twig', [
            'entreprise' =>$entreprise,
            'modeles' => $modeles,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @throws Exception
     */
    #[Route('/{id}/save/offre', name: 'entreprise_save_offre', methods: ['GET', 'POST'])]
    public function creationOffre(Request $request, Entreprise $entreprise): Response
    {
        $modeleId = $request->get('modeleId');
        if (!$modeleId){
            return $this->redirect($this->generateUrl('entreprise_offres_commerciales', ['id'=>$entreprise->getId()]));
        }
        $this->saveOffreModele($entreprise->getId(), $modeleId);

        $this->addFlash('success', 'Ajout réussi');

        return $this->redirect($this->generateUrl('entreprise_offres_commerciales', ['id'=>$entreprise->getId()]));
    }

    #[Route('/{id}/edit', name: 'entreprise_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Entreprise $entreprise): Response
    {
        $form = $this->createForm(EntrepriseType::class, $entreprise);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('logo')->getData()){
                $this->uploadFile($form->get('logo')->getData(), $entreprise);
            }

            $entreprise->updateTimestamps();
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($entreprise);
            $entityManager->flush();

            $this->addFlash('success', 'Mise à jour réussie');

            return $this->redirectToRoute('entreprise_index');
        }

        return $this->render('entreprise/edit.html.twig', [
            'entreprise' => $entreprise,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}/factures', name: 'entreprise_factures')]
    public function factures(Request $request, Entreprise $entreprise, FactureRepository $factureRepository): Response
    {
        $factures = $factureRepository->findBy([
            'entreprise' => $entreprise,
        ]);
        return $this->render('entreprise/factures.html.twig', [
            'entreprise' =>$entreprise,
            'factures' => $factures,
        ]);
    }

    #[Route('/{id}/recruteur', name: 'entreprise_recruteurs', methods: ['GET', 'POST'])]
    public function recruteurs(Request $request, Entreprise $entreprise): Response
    {
        $user = new User();

        $entityManager = $this->getDoctrine()->getManager();
        $form = $this->createForm(UserType::class, $user);

        $nbMaxRecruteurs = $entityManager->getRepository(Entreprise::class)->getNbMaxRecruteurs($entreprise->getId());
        $nbRecruteurs = count($entreprise->getRecruteurs()) + count($entreprise->getSuperRecruteurs());

        return $this->render('entreprise/recruteurs.html.twig', [
            'user' => $user,
            'nbMaxRecruteurs' => $nbMaxRecruteurs,
            'nbRecruteurs' => $nbRecruteurs,
            'entreprise' =>$entreprise,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @throws TransportExceptionInterface
     */
    #[Route('/{id}/recruteur/create', name: 'entreprise_recruteurs_create', methods: ['GET', 'POST'])]
    public function recruteurCreate(Request $request, Entreprise $entreprise, UserRepository $userRepository, UserPasswordEncoderInterface $passwordEncoder, MailerInterface $mailer): Response
    {
        $userExist = $userRepository->findOneBy(['email' =>$request->get('user[email]')]);
        $password = $userRepository->genererMDP();
        if ($userExist){
            $user = $userExist;
        }else{
            $user = new User();
        }

        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);
        $entityManager = $this->getDoctrine()->getManager();

        if ($form->isSubmitted() && $form->isValid()) {

            $superRecruteur = $request->get('super_recruteur');
            if (!$userExist) {
                $user->setPassword(
                    $passwordEncoder->encodePassword(
                        $user,
                        $password
                    )
                );
            }
            $user->setActivationToken(md5(uniqid()));
            $user->setForgotPasswordTokenRequestedAt(new \DateTimeImmutable('now'));

            if ($superRecruteur){
                $entreprise->addSuperRecruteur($user);
                $user->setRoles(['ROLE_SUPER_RECRUTEUR']);
            }else{
                $entreprise->addRecruteur($user);
                $user->setRoles(['ROLE_RECRUTEUR']);
            }

            $entityManager->persist($user);
            $entityManager->persist($entreprise);
            $entityManager->flush();

            $email = (new TemplatedEmail())
                ->from('haha@gmail.com')
                ->to($user->getEmail())
                ->subject('Activation de compte!')
                ->text('Sending emails is fun again!')
                ->htmlTemplate('emails/creation.html.twig')
                ->context([
                    'password' => $password, 'user' => $user,
                    'token' => $user->getActivationToken()
                ])
            ;

            $mailer->send($email);

            $this->addFlash('success', 'Ajout réussi');

            return $this->redirectToRoute('entreprise_recruteurs',['id' => $entreprise->getId()], Response::HTTP_SEE_OTHER);
        }

        $response = new Response(null, $form->isSubmitted() ? 422 : 200);

        return $this->render('user_creation/index.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ], $response);

        //return $this->redirect($this->generateUrl('entreprise_recruteurs',['id' => $entreprise->getId()]));
    }

    /**
     * @param $userID
     * @param Request $request
     * @param Entreprise $entreprise
     * @param UserRepository $userRepository
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @param MailerInterface $mailer
     * @return Response
     * @throws TransportExceptionInterface
     */
    #[Route('/{id}/recruteur/generateMDP/{userID}', name: 'entreprise_recruteurs_generateMDP', methods: ['GET', 'POST'])]
    public function generateMDPRecruteurs($userID, Request $request, Entreprise $entreprise, UserRepository $userRepository, UserPasswordEncoderInterface $passwordEncoder, MailerInterface $mailer): Response
    {
        $password = $userRepository->genererMDP();
        $user = $userRepository->find($userID);

        $user->setPassword(
            $passwordEncoder->encodePassword(
                $user,
                $password
            )
        );

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($user);
        $entityManager->flush();

        $email = (new TemplatedEmail())
            ->from('haha@gmail.com')
            ->to($user->getEmail())
            ->subject('Modification de mot de passe')
            ->text('Sending emails is fun again!')
            ->htmlTemplate('emails/modification_mot_passe.html.twig')
            ->context([
                'password' => $password, 'user' => $user,
            ])
        ;

        $mailer->send($email);

        $this->addFlash('success', 'Le mot de passe a été généré avec succès');
        return $this->redirectToRoute('entreprise_recruteurs',['id' => $entreprise->getId()], Response::HTTP_SEE_OTHER);
    }

    /**
     * @param $userID
     * @param Request $request
     * @param Entreprise $entreprise
     * @param UserRepository $userRepository
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @param MailerInterface $mailer
     * @return Response
     */
    #[Route('/{id}/recruteur/deleteRecruteur/{userID}', name: 'entreprise_recruteurs_delete', methods: ['GET', 'POST'])]
    public function deleteRecruteur($userID, Request $request, Entreprise $entreprise, UserRepository $userRepository, UserPasswordEncoderInterface $passwordEncoder, MailerInterface $mailer): Response
    {
        $user = $userRepository->find($userID);
        $entityManager = $this->getDoctrine()->getManager();
        if ($entreprise->getRecruteurs()->contains($user)){
            $entreprise->removeRecruteur($user);
        }

        if ($entreprise->getSuperRecruteurs()->contains($user)){
            $entreprise->removeSuperRecruteur($user);
        }

        $entityManager->persist($entreprise);
        $entityManager->flush();

        $this->addFlash('success', 'La suppression du recruteur '. $user->getFullname() .' a été faite avec succès');

        return $this->redirectToRoute('entreprise_recruteurs',['id' => $entreprise->getId()], Response::HTTP_SEE_OTHER);
    }

    #[Route('/{id}', name: 'entreprise_delete', methods: ['POST'])]
    public function delete(Request $request, Entreprise $entreprise): Response
    {
        if ($this->isCsrfTokenValid('delete'.$entreprise->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($entreprise);
            $entityManager->flush();
        }

        return $this->redirectToRoute('entreprise_index');
    }

    #[Route('/supprime/file/{id}', name: 'entreprise_delete_files', methods: ['DELETE'])]
    public function deleteImage(File $file, Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if($this->isCsrfTokenValid('delete'.$file->getId(), $data['_token'])){
            $nom = $file->getName();
            unlink($this->getParameter('files_directory').'/'.$nom);

            $em = $this->getDoctrine()->getManager();
            $em->remove($file);
            $em->flush();

            return new JsonResponse(['success' => 1]);
        }else{
            return new JsonResponse(['error' => 'Token Invalide'], 400);
        }
    }

    /**
     * @param $id
     * @param $idModel
     * @throws Exception
     */
    public function saveOffreModele($id, $idModel)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $entreprise = $entityManager->getRepository('App:Entreprise')->find($id);
        $modele = $entityManager->getRepository('App:ModeleOffreCommerciale')->find($idModel);

        $now = new \DateTime('now');
        $duree = "+" . $modele->getDureeContrat() . " month";
        $dateFin = new \DateTime('now' . $duree);

        $formule = new Offre();
        $formule->setPrix($modele->getPrix());
        $formule->setFormule($modele->getName());
        //if ($modele->getNombreOffres()){
            $formule->setNombreOffres($modele->getNombreOffres());
        //}

        $formule->setIsCvTheque($modele->getIsCvTheque());
        $formule->setEntreprise($entreprise);
        $formule->setNombreRecruteurs($modele->getNombreRecruteurs());
        $formule->setDebutContratAt($now);
        $formule->setFinContratAt($dateFin);
        $formule->setIsFacture(0);
        $formule->setModeleOffreCommerciale($modele);
        $entityManager->persist($formule);

        $entityManager->flush();
    }

    /**
     * @param $file
     * @param $entreprise
     */
    public function uploadFile($file, $entreprise)
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
        $entreprise->addLogo($img);
    }
}
