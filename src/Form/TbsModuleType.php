<?php

namespace App\Form;

use App\Entity\TbsModule;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\All;
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
                    'placeholder' => 'Module Beschreibung',
                    'rows' => 10,
                ],
                'label' => 'Beschreibung',
                'required' => true
            ])


            ->add('typo3Version',ChoiceType::class,[
                'label' => 'TYPO3 Version *',
                'choices'  => [
                    '11-dev' => '11-dev',
                    '10.4' => '10 LTS',
                    '9.5' => '9 LTS',
                ],
                'expanded' => true,
                'multiple' => true,
                'required' => true,

            ])
            ->add('link',UrlType::class,[
                'label' => 'Link Beispiel',
                'required' => false
            ])
            ->add('moduleImages', FileType::class, array(
                    'multiple' => true,
                    'mapped' => false,
                    'required' => false,

                    // unmapped fields can't define their validation using annotations
                    // in the associated entity, so you can use the PHP constraint classes
                    'constraints' => [
                        new All([
                            new File([
                                'mimeTypes' => [
                                    'image/jpeg',
                                    'image/png'
                                ],
                            ])
                        ])
                    ],
                    'attr' => [
                        'accept' => '.jpg, .jpeg, .png'
                    ],
                )
            )
            ->add('tsConfigCode', TextareaType::class,[])
            ->add('typoScriptCode', TextareaType::class,[])
            ->add('ttContentCode', TextareaType::class,[])
            ->add('sqlOverrideCode', TextareaType::class,[])
            ->add('sqlNewTableCode', TextareaType::class,[])
            ->add('backendPreviewCode', TextareaType::class,[])
            ->add('htmlCode', TextareaType::class,[])


        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => TbsModule::class,
        ]);
    }
}
