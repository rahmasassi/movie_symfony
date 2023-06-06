<?php

namespace App\Form;

use App\Entity\Genre;
use App\Entity\Movie;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class MovieType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('year')
            ->add('mainCast')
            ->add('synopsis')
            ->add('director')
            ->add('code')
            ->add('genres', EntityType::class,[
                'class' => Genre::class,
                'choice_label'=> 'type',
                'multiple'=> true,
                'expanded' => true,
            ])
            ->add('rating', ChoiceType::class,[
                'choices' =>[
                    '1 star' => 1,
                    '2 stars' => 2,
                    '3 stars' => 3,
                    '4 stars' => 4,
                    '5 stars' => 5,
                ]
            ])
            ->add('video', FileType::class, [
                'label' => 'Upload video',
                'mapped' => false,
                'required' => true,
                'constraints' => [
                    new File([
                        'maxSize' => '2048000k',
                        'mimeTypes' => [
                            'video/mp4',

                        ],
                        'mimeTypesMessage' => 'Please upload a valid Video',
                    ])
                ],
            ]);
    }
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Movie::class,
        ]);
    }
}
