<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\Form;
use Symfony\Component\Form\FormInterface;
use AppBundle\Entity\Type;
use AppBundle\Entity\Description;
use AppBundle\Entity\Image;

class AnimalEditType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class)
            ->add('birthday', DateType::class, array(
                'widget' => 'single_text'
            ))
            ->add('sterilised', CheckboxType::class)
            ->add('identificationNumber', TextType::class, array('required' => false))
            ->add('sex', EntityType::class, array('class' => 'AppBundle:Sex', 'choice_label' => 'type', 'choice_attr' => function () { return array('class' => 'flat', 'name' => 'iCheck'); } , 'expanded' => 'true'), array('required' => false))
            ->add('breed', EntityType::class, array(
                'class' => 'AppBundle:Breed',
                'choice_label' => 'name'
            ))
            ->add('typeIdentification', EntityType::class, array('class' => 'AppBundle:TypeIdentification', 'choice_label' => 'name',  'choice_attr' => function () { return array('class' => 'flat', 'name' => 'iCheck'); } , 'expanded' => 'true'), array('required' => false))
            ->add('description', TextAreaType::class, array('required' => false))
            ->add('Enregistrer', SubmitType::class)
        ;


    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Animal'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_animal';
    }


}
