<?php

namespace App\Form;

use App\Entity\Client;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ClientType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class,[
                'attr' => [
                    'placeholder' => 'Name'
                ],
                'required' => true,
                'label' => 'Name'
            ])
            ->add('email', EmailType::class,[
                'attr' => [
                    'placeholder' => 'E-Mail'
                ],
                'required' => true,
                'label' => 'E-Mail'
            ])
            ->add('company', TextType::class,[
                'attr' => [
                    'placeholder' => 'Agentur/Firma'
                ],
                'required' => true,
                'label' => 'Agentur/Firma'
            ])
            ->add('homePage', UrlType::class,[
                'attr' => [
                    'placeholder' => 'Link'
                ],
                'required' => true,
                'label' => 'Link'
            ])
            ->add('description', TextareaType::class,[
                'attr' => [
                    'placeholder' => 'Beschreibung'
                ],
                'label' => 'Beschreibung',
                'required' => false
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Client::class,
        ]);
    }
}
