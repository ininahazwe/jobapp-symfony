<?php

namespace App\Form;

use App\Entity\Offre;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OffreType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('formule')

            ->add('nombre_offres')
            ->add('debutContratAt', DateTimeType::class, [
                'date_widget' => 'single_text',
            ])
            ->add('finContratAt', DateTimeType::class, [
                'date_widget' => 'single_text',
            ])
            ->add('isCvTheque')
            ->add('prix')
            ->add('facture')
            ->add('entreprise')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Offre::class,
        ]);
    }
}
