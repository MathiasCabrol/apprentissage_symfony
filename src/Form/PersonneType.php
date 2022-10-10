<?php

namespace App\Form;

use App\Entity\Job;
use App\Entity\Hobby;
use App\Entity\Profile;
use App\Entity\Personne;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class PersonneType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstname')
            ->add('name')
            ->add('age')
            ->add('created_at')
            ->add('updated_at')
            ->add('profile', EntityType::class, [
                'expanded' => false,
                'multiple' => true,
                'class' => Profile::class,
                'attr' => [
                    'class' => 'select2',
                ], 
                'required' => false,
            ])
            ->add('hobbies', EntityType::class, [
                'expanded' => false,
                'multiple' => true,
                'class' => Hobby::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('h')
                        ->orderBy('h.designation', 'ASC');
                },
                'choice_label' => 'designation',
                'attr' => [
                    'class' => 'select2',
                ],
                'required' => false,
            ])
            ->add('photo', FileType::class, [
                'label' => 'Votre image de profil (Fichiers images uniquement)',
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '10000k',
                        'mimeTypes' => [
                            'image/jpeg',
                            'image/jpg',
                            'image/gif',
                        ],
                        'mimeTypesMessage' => 'Please upload a valid Image',
                    ])
                ],
            ])
            ->add('job', EntityType::class, [
                'class' => Job::class,
                'attr' => [
                    'class' => 'select2',
                ]
            ])
            ->add('editer', SubmitType::class);
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Personne::class,
        ]);
    }
}
