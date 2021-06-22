<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

#[Route('/user/creation')]
class UserCreationController extends AbstractController
{
    #[Route('/', name: 'user_creation', methods: ['GET'])]
    public function index(UserRepository $userRepository): Response
    {
        return $this->render('user_creation/index.html.twig', [
            'users' => $userRepository->getRecruteurs($this->getUser()->getId()),
        ]);
    }

    /*#[Route('/new', name: 'user_creation_new', methods: ['GET', 'POST'])]
    public function new(Request $request, UserPasswordEncoderInterface $passwordEncoder, MailerInterface $mailer): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setRoles(['ROLE_RECRUTEUR']);
            $user->setPassword(
                $passwordEncoder->encodePassword(
                    $user,
                    $form->get('password')->getData()
                )
            );

            $user->setActivationToken(md5(uniqid()));

            $entityManager = $this->getDoctrine()->getManager();

            $entreprises = $form->get('entreprises')->getData();

            foreach ($entreprises as $entrepriseId){
                $entreprise = $entityManager->getRepository('App\Entity\Entreprise')->find($entrepriseId);
                $entreprise->addRecruteur($user);
            }

            $entityManager->persist($user);
            $entityManager->flush();

            $email = (new TemplatedEmail())
                ->from('haha@gmail.com')
                ->to('oyo@gmail.com')
                ->subject('Activation de compte!')
                ->text('Sending emails is fun again!')
                ->htmlTemplate('emails/creation.html.twig')
                ->context(['token' => $user->getActivationToken()])
            ;

            $mailer->send($email);

            $this->addFlash('success', 'Le compte a été créé');
            return $this->redirectToRoute('user_creation');
        }

        return $this->render('user_creation/index.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }*/

    #[Route('/{id}', name: 'user_creation_show', methods: ['GET'])]
    public function show(User $user): Response
    {
        return $this->render('user_creation/show.html.twig', [
            'user' => $user,
        ]);
    }

    #[Route('/{id}/edit', name: 'user_creation_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, User $user): Response
    {
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('user_creation');
        }

        return $this->render('user_creation/edit.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'user_creation_delete', methods: ['POST'])]
    public function delete(Request $request, User $user): Response
    {
        if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($user);
            $entityManager->flush();
        }

        return $this->redirectToRoute('user_creation');
    }

    #[Route('/activation/{token}', name: 'activation')]
    public function activation($token, UserRepository $userRepository): Response
    {
        $user = $userRepository->findOneBy(['activation_token' => $token]);
        if(!$user){
            throw $this->createNotFoundException('Cet utilisateur n\'existe pas');
        }

        $user->setActivationToken(null);
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($user);
        $entityManager->flush();

        $this->addFlash('success', 'Vous avez bien activé votre compte');

        return $this->render('home/index.html.twig');
    }

}
