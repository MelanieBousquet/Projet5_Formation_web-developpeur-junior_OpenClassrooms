<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class PageType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('published', CheckboxType::class, array('label' => 'Publié', 'label_attr' => array('class' => 'control-label col-md-3 col-sm-3 col-xs-12'), 'required' => false))
            ->add('title', TextType::class, array('label' => 'Titre', 'label_attr' => array('class' => 'control-label col-md-3 col-sm-3 col-xs-12')))
            ->add('nameInMenu', TextType::class, array('label'=> 'Nom dans le menu', 'label_attr' => array('class' => 'control-label col-md-3 col-sm-3 col-xs-12')))
            ->add('content', TextAreaType::class, array('label' => 'Contenu', 'required' => false, 'label_attr' => array('class' => 'control-label col-md-3 col-sm-3 col-xs-12')))
            ->add('image', ImageType::class, array('required' => false, 'label_attr' => array('class' => 'control-label col-md-3 col-sm-3 col-xs-12')))
            ->add('submit', SubmitType::class, array('label' => "Enregistrer"))
        ;
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Page'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_page';
    }


}
