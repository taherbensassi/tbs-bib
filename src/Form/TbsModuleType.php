<?php

namespace App\Form;

use App\Entity\TbsModule;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class TbsModuleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class,[
                'attr' => [
                    'placeholder' => 'Module Titel'
                ],
                'required' => true,
                'label' => 'Module Titel'
            ])

            ->add('description', TextareaType::class,[
                'attr' => [
                    'placeholder' => 'Module Beschreibung'
                ],
                'label' => 'Beschreibung',
                'required' => false
            ])

            ->add('image', FileType::class, [
                'label' => 'Bild',

                // unmapped means that this field is not associated to any entity property
                'mapped' => false,

                // make it optional so you don't have to re-upload the PDF file
                // every time you edit the Product details
                'required' => false,

                // unmapped fields can't define their validation using annotations
                // in the associated entity, so you can use the PHP constraint classes
                'constraints' => [
                    new File([
                        'maxSize' => '1024k',
                        'mimeTypes' => [
                            'image/png',
                            'image/jpeg',
                        ],
                        'mimeTypesMessage' => 'Bitte laden Sie ein gültiges Bild hoch',
                    ])
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => TbsModule::class,
        ]);
    }
}
