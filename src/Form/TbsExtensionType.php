<?php

namespace App\Form;

use App\Entity\TbsExtension;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\ChoiceList\Loader\CallbackChoiceLoader;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;
use App\Api\ApiVersionChoiceList;

class TbsExtensionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class,[
                'attr' => [
                    'placeholder' => 'My Sitepackage'
                ],
                'required' => true,
                'label' => 'Titel'
            ])
            ->add('extensionKey', TextType::class,[
                'attr' => [
                    'placeholder' => 'Extension Key'
                ],
                'required' => true,
                'label' => 'Extension Key'
            ])
            ->add('extensionZip', FileType::class, [
                'label' => 'Extension',

                // unmapped means that this field is not associated to any entity property
                'mapped' => false,

                // make it optional so you don't have to re-upload the PDF file
                // every time you edit the Product details
                'required' => false,

                // unmapped fields can't define their validation using annotations
                // in the associated entity, so you can use the PHP constraint classes
                'constraints' => [
                    new File([
                        'mimeTypes' => [
                            'application/zip',
                        ],
                        'mimeTypesMessage' => 'Bitte laden Sie ein gültiges Bild hoch',
                    ])
                ],
                'attr' => [
                    'accept' => '.zip'
                ],
            ])
            ->add('description', TextareaType::class,[
                'attr' => [
                    'placeholder' => 'Module Beschreibung',
                    'rows' => 10,
                ],
                'label' => 'Beschreibung',
                'required' => true
            ])
            ->add('link',UrlType::class,[
                'label' => 'Dokumetation (Git,Confluence)',
                'required' => true
            ])


        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => TbsExtension::class,
        ]);
    }
}
