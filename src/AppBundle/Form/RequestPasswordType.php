<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\FormError;


class RequestPasswordType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {     
        $builder->add('identifier', TextType::class, array('label' => 'Email ou Pseudo', 'label_attr' => array('class'=> 'col-md-2')));

    }
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'AppBundle\Entity\Password\RequestPassword',
        ]);
    }
}