<?php

namespace App\Form;

use App\Entity\User;
use App\Entity\Dictionnaire;
use App\Repository\EntrepriseRepository;
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
    private User $user;
    private EntrepriseRepository $entrepriseRepository;


    public function __construct(User $user, EntrepriseRepository $entrepriseRepository)
    {
        $this->user = $user;
        $this->entrepriseRepository = $entrepriseRepository;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $user = $this->user;
        $entrepriseRepository = $this->entrepriseRepository;
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
                'class' => 'App\Entity\Entreprise',
                'query_builder' => function($repository) use($entrepriseRepository , $user) {
                    $query = $entrepriseRepository->filtrerEntrepriseParUtilisateur($user->getId());

                    return $query;
                    /*$query = $repository->createQueryBuilder('d')
                        ->select('d')
                        ->where('d.id = 1');

                    return $query;*/
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
            'query_builder' => function($repository) use($user) {
                if ($user->getRoles("ROLE_SUPER_ADMIN_HANDICV")) {
                    $query = $repository->createQueryBuilder('u')
                        ->select('u')
                        ->andWhere('u.id = 5')
                        ->addOrderBy('u.createdAt', 'ASC')
                    ;

                }else{
                    $query = $repository->createQueryBuilder('u')
                        ->select('u')
                        ->addOrderBy('u.createdAt', 'DESC')
                    ;

                }
                return $query;
            },
        ]);
    }
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            //'data_class' => Annonce::class,
            'csrf_protection' => false,
            'user' => $this->user,
            'entrepriseRepository' => $this->entrepriseRepository
        ]);
    }
}
