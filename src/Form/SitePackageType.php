<?php

namespace App\Form;

use App\Entity\SitePackage;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SitePackageType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('typo3Version')
            ->add('basePackage')
            ->add('title')
            ->add('description')
            ->add('repositoryUrl')
            ->add('author')
            ->add('authorEmail')
            ->add('authorCompany')
            ->add('authorHomePage')

        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => SitePackage::class,
        ]);
    }
}
