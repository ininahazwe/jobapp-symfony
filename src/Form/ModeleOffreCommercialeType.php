<?php

namespace App\Form;

use App\Entity\ModeleOffreCommerciale;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ModeleOffreCommercialeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('slug')
            ->add('nombre_offres', IntegerType::class,[
                'required' => false
            ])
            ->add('isCvTheque')
            ->add('nombreRecruteurs', IntegerType::class, [
                'required' => false
            ])
            ->add('dureeContrat')
            ->add('prix')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ModeleOffreCommerciale::class,
        ]);
    }
}
