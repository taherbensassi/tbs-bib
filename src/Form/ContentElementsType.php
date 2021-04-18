<?php

namespace App\Form;

use App\Entity\ContentElements;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ContentElementsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('elementKey', TextType::class,[
                'attr' => [
                    'placeholder' => 'Element-key (unique, lowercase)'
                ],
                'required' => true,
                'label' => 'Element-Key'
            ])
            ->add('elementTitle', TextType::class,[
                'attr' => [
                    'placeholder' => 'Titel'
                ],
                'required' => true,
                'label' => 'Titel'
            ])
            ->add('shortTitle', TextType::class,[
                'attr' => [
                    'placeholder' => 'Kurztitel'
                ],
                'required' => true,
                'label' => 'Kurztitel'
            ])
            ->add('discription', TextareaType::class,[
                'attr' => [
                    'placeholder' => 'Beschreibung'
                ],
                'required' => true,
                'label' => 'Beschreibung'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ContentElements::class,
        ]);
    }
}
