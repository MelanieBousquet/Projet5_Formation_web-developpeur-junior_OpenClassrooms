<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class ContactType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('address', TextType::class, array('label' => 'Adresse *', 'label_attr' => array('class' => 'control-label col-md-3 col-sm-3 col-xs-12')))
            ->add('phonenumber', TextType::class, array('label' => 'Numéro de téléphone *', 'label_attr' => array('class' => 'control-label col-md-3 col-sm-3 col-xs-12')))
            ->add('email', TextType::class, array('label' => 'Email', 'label_attr' => array('class' => 'control-label col-md-3 col-sm-3 col-xs-12'), 'required' => false))
            ->add('facebook', TextType::class, array('label' => 'Facebook', 'label_attr' => array('class' => 'control-label col-md-3 col-sm-3 col-xs-12'), 'required' => false))
            ->add('twitter', TextType::class, array('label' => 'Twitter', 'label_attr' => array('class' => 'control-label col-md-3 col-sm-3 col-xs-12'), 'required' => false))
            ->add('submit', SubmitType::class, array('label' => "Enregistrer"))
        ;
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Contact'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_contact';
    }


}
