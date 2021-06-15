<?php

namespace App\Form;

use App\Entity\User;
use App\Form\FormExtension\HoneyPotType;
use App\Form\FormExtension\RepeatedPasswordType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;

class RegistrationFormType extends HoneyPotType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        parent::buildForm($builder, $options);
        $builder
            ->add('email', EmailType::class, [
                'label'         => "Email",
                'required'      => true,
                'attr' => [
                    'autofocus' => true
                ]
            ])
            ->add('password', RepeatedPasswordType::class)
            ->add('username', TextType::class, [
                'label'         => "Nom d'utilisateur",
                'required'      => true,
                'attr' => [
                    'autofocus' => true
                ]
            ])
            ->add('firstname', TextType::class, [
                'label'         => "Prénom",
                'required'      => true,
                'attr' => [
                    'autofocus' => true
                ]
            ])
            ->add('lastname', TextType::class, [
                'label'         => "Nom",
                'required'      => true,
                'attr' => [
                    'autofocus' => true
                ]
            ])
            ->add('agreeTerms', CheckboxType::class, [
                'label'             => "J'accepte les conditions d'utilisation",
                'mapped'            => false,
                'required'          => true,
                'constraints'       => [
                    new IsTrue([
                        'message'   => 'Vous devez accepter les conditions d\'utilisation de ce site pour vous inscrire.',
                    ]),
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
