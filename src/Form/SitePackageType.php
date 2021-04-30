<?php

namespace App\Form;

use App\Entity\SitePackage;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\RadioType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SitePackageType extends AbstractType
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

            ->add('typo3Version',ChoiceType::class,[
                'required' => true,
                'label' => 'TYPO3 Version *',
                'choices'  => [
                    '10.4' => 10004000,
                    '9.5' => 9005000,
                    '8.7' => 8007000,
                ],
                'expanded' => true,
            ])
            ->add('basePackage',ChoiceType::class,[
                'required' => true,
                'label' => 'Base Package *',
                'choices'  => [
                    'Bootstrap Package' => 'bootstrap_package',
                    'Fluid Styled Content' => 'fluid_styled_content',
                ],
                'expanded' => true,
            ])
            ->add('description', TextareaType::class,[
                'attr' => [
                    'placeholder' => 'Extension Beschreibung'
                ],
                'label' => 'Beschreibung',
                'required' => false
            ])
            ->add('repositoryUrl', UrlType::class,[
                'attr' => [
                    'placeholder' => 'https://gitlab.brettinghams-dev.de/'
                ],
                'label' => 'Repository Url',
                'required' => false
            ])


        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => SitePackage::class,
        ]);
    }
}
