<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use App\Form\DataTransformer\FrenchToDateTimeTransformer;
use Symfony\Component\Form\Extension\Core\Type\EmailType;

class ProfileFormType extends AbstractType
{
    private $transformer;

    public function __construct(FrenchToDateTimeTransformer $transformer) {
        $this->transformer = $transformer;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('firstName', TextType::class, [
            'label' => 'Prénom',
            'attr' => [
                'class' => 'form-input',
                'placeholder' => 'Prénom'
            ]
        ])
        ->add('lastName', TextType::class, [
            'label' => 'Nom',
            'attr' => [
                'class' => 'form-input',
                'placeholder' => 'Nom'
            ]
        ])
        ->add('birthday', TextType::class, [
            'label' => 'Date de naissance',
            'attr' => [
                'class' => 'form-input',
                'placeholder' => 'Date de naissance'
            ],
            'invalid_message' => 'Vous devez renseigner une date de naissance valide'
        ])
        ->add('name', TextType::class, [
            'label' => 'Nom d\'utilisateur',
            'attr' => [
                'class' => 'form-input',
                'placeholder' => 'Nom d\'utilisateur'
            ]
        ])
        ->add('email', EmailType::class, [
            'label' => 'Adresse email',
            'attr' => [
                'class' => 'form-input',
                'placeholder' => 'Adresse email'
            ]
        ])
        ;
        
        $builder->get('birthday')->addModelTransformer($this->transformer);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
