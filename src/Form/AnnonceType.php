<?php

namespace App\Form;

use App\Entity\Entreprise;
use App\Entity\User;
use App\Entity\Dictionnaire;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AnnonceType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $user = $options['user'];

        $builder
            ->add('name', TextType::class)
            ->add('description', TextareaType::class)
            ->add('isActive')
            ->add('diplome', EntityType::class, [
                'required'  => true,
                'label' => 'Diplôme requis',
                'expanded' => false,
                'class' => 'App\Entity\Dictionnaire',
                'query_builder' => function($repository) {
                    $query = $repository->createQueryBuilder('d')
                        ->select('d')
                        ->where('d.type = :type')
                        ->setParameter('type', Dictionnaire::TYPE_DIPLOMA);

                    return $query;
                }
            ])
            ->add('experience', EntityType::class, [
                'required'  => false,
                'label' => 'Experience',
                'expanded' => false,
                'class' => 'App\Entity\Dictionnaire',
                'query_builder' => function($repository) {
                    $query = $repository->createQueryBuilder('d')
                        ->select('d')
                        ->where('d.type = :type')
                        ->setParameter('type', Dictionnaire::TYPE_EXPERIENCE);

                    return $query;
                }
            ])
            ->add('location', EntityType::class, [
                'required'  => false,
                'label' => 'Zone géographique',
                'expanded' => false,
                'multiple' => true,
                'class' => 'App\Entity\Dictionnaire',
                'query_builder' => function($repository) {
                    $query = $repository->createQueryBuilder('d')
                        ->select('d')
                        ->where('d.type = :type')
                        ->setParameter('type', Dictionnaire::TYPE_LOCATION);

                    return $query;
                }
            ])
            ->add('entreprise', EntityType::class ,[
                'class' => Entreprise::class,
                'query_builder' => function($repository) use($user) {
                    if ($user->isSuperAdmin() ){
                        $query = $repository->createQueryBuilder('d')
                            ->select('d')
                            ->orderBy('d.name' ,  'ASC');
                        return $query;
                    }elseif($user->isSuperRecruteur()){
                        $ids = array();
                        foreach($user->getRecruteursEntreprise() as $entreprise){
                            if (!in_array($entreprise->getId(), $ids)){
                                $ids[$entreprise->getId()] = $entreprise->getId();
                            }
                        }
                        foreach($user->getEntreprises() as $entreprise){
                            if (!in_array($entreprise->getId(), $ids)){
                                $ids[$entreprise->getId()] = $entreprise->getId();
                            }
                        }

                        $query = $repository->createQueryBuilder('d')
                            ->andWhere('d.id IN (:ids)')
                            ->orderBy('d.name' ,  'ASC')
                            ->setParameter('ids',$ids)
                        ;
                    return $query;

                    }elseif($user->isRecruteur())
                    {
                        $ids = array();
                        foreach($user->getEntreprises() as $entreprise){
                            if (!in_array($entreprise->getId(), $ids)){
                                $ids[$entreprise->getId()] = $entreprise->getId();
                            }
                        }

                        $query = $repository->createQueryBuilder('d')
                            ->andWhere('d.id IN (:ids)')
                            ->orderBy('d.name' ,  'ASC')
                            ->setParameter('ids',$ids)
                        ;
                        return $query;
                    }else{
                        return null;
                    }
                }
            ])
            //->add('auteur')
            ->add('reference')
            ->add('dateLimiteCandidature', DateTimeType::class, [
                'date_widget' => 'single_text',
                'with_minutes' => false,
                'with_seconds' => false
            ])
            //TODO enlever l'heure
            ->add('type_contrat', EntityType::class, [
                'required'  => false,
                'label' => 'Type de contrat',
                'expanded' => false,
                'multiple' => true,
                'class' => Dictionnaire::class,
                'query_builder' => function($repository) {
                    $query = $repository->createQueryBuilder('d')
                        ->select('d')
                        ->where('d.type = :type')
                        ->setParameter('type', Dictionnaire::TYPE_CONTRACT);

                    return $query;
                }
            ])
            ->add('adresse_email')
            ->add('lien')
            ->add('Valider', SubmitType::class)
        ;
        $builder->add('auteur', EntityType::class,[
            'required' => false,
            'label'	=> "Gestionnaire(s) de l'offre d'emploi",
            'multiple' => true,
            'expanded' => true,
            'class' => User::class,
            //'property' => 'firstname',
            'query_builder' => function($repository) use($user) {
                if ($user->isSuperAdmin() ){
                    $query = $repository->createQueryBuilder('d')
                        ->select('d');
                        //->orderBy('d.fullName' ,  'ASC');
                    return $query;
                }elseif($user->isSuperRecruteur()){
                    $ids = array();
                    foreach($user->getRecruteursEntreprise() as $recruteur){
                        if (!in_array($recruteur->getId(), $ids)){
                            $ids[$recruteur->getId()] = $recruteur->getId();
                        }
                    }
                    foreach($user->getEntreprises() as $recruteur){
                        if (!in_array($recruteur->getId(), $ids)){
                            $ids[$recruteur->getId()] = $recruteur->getId();
                        }
                    }

                    $query = $repository->createQueryBuilder('d')
                        ->andWhere('d.id IN (:ids)')
                        //->orderBy('d.name' ,  'ASC')
                        ->setParameter('ids',$ids)
                    ;
                    return $query;

                }elseif($user->isRecruteur())
                {
                    $ids = array();
                    foreach($user->getEntreprises() as $recruteur){
                        if (!in_array($recruteur->getId(), $ids)){
                            $ids[$recruteur->getId()] = $recruteur->getId();
                        }
                    }

                    $query = $repository->createQueryBuilder('d')
                        ->andWhere('d.id IN (:ids)')
                        //->orderBy('d.name' ,  'ASC')
                        ->setParameter('ids',$ids)
                    ;
                    return $query;
                }else{
                    return null;
                }
            }
        ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            //'data_class' => Annonce::class,
            'csrf_protection' => true,
        ]);
        $resolver->setRequired([
            'user',
        ]);
    }
}
