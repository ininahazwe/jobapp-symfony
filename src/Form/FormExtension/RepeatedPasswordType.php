<?php

namespace App\Form\FormExtension;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RepeatedPasswordType extends AbstractType
{
    public function getParent(): string
    {
        return RepeatedType::class;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'type'                  => PasswordType::class,
            'invalid_message'       => "Passwords are different ",
            'required'              => true,
            'first_options'         => [
                'label'             => "Password",
                'label_attr'        => [
                    'title'         => 'For security reasons, your password must contains...',
                ],
                'attr'              => [
                    'title'         => "For security reasons, your password must contains",
                    'maxlength'     => 255
                ]
            ],
            'second_options'        => [
                'label'             => "Confirm password",
                'label_attr'        => [
                    'title'         => 'Confirm password'
                ],
                'attr'              => [
                    'title'         => "Confirm  password",
                    'maxlength'     => 255
                ]
            ],
        ]);
    }
}