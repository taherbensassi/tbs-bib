<?php

namespace App\Form;

use App\Entity\ExportContentElement;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ExportContentElementType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('extensionName', TextType::class,[
                'attr' => [
                    'placeholder' => 'Extension Name'
                ],
                'required' => true,
                'label' => 'Extension Name'
            ])
            ->add('vendorName', TextType::class,[
                'attr' => [
                    'placeholder' => 'Vendor Name (Tbs)'
                ],
                'required' => true,
                'label' => 'Vendor Name'
            ])
            ->add('icon')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ExportContentElement::class,
        ]);
    }
}
