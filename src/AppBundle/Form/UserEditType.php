<?php

namespace AppBundle\Form;

use AppBundle\Entity\User;
use AppBundle\Entity\Image;
use AppBundle\Form\ImageType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class UserEditType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('userPseudo', TextType::class, array('label' => 'Nom d\'utilisateur', 'label_attr' => array('class' =>'col-md-2')))
            ->add('email', EmailType::class, array('label_attr' => array('class' =>'col-md-2')))
            ->add('Confirmer', SubmitType::class, array('attr'=> array('class' =>'btn btn-default')))
        ;
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\User'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_user';
    }


}
