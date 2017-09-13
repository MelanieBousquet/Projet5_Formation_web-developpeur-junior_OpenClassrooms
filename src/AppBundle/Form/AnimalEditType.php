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
            ->add('type', EntityType::class, array('class' => 'AppBundle:Type', 'placeholder' => 'Sélectionner un type d\'animal', 'choice_label' => 'name', 'mapped' => false))
            ->add('typeIdentification', EntityType::class, array('class' => 'AppBundle:TypeIdentification', 'choice_label' => 'name',  'choice_attr' => function () { return array('class' => 'flat', 'name' => 'iCheck'); } , 'expanded' => 'true'), array('required' => false))
            ->add('description', TextType::class, array('required' => false))
            ->add('animalStates', CollectionType::class, array(
                'entry_type' => AnimalStateType::class,
                'allow_add' => 'true',
                'allow_delete' => 'true'
            ))
            ->add('Enregistrer', SubmitType::class)

        /*  ->add('tags', array('required' => false)) */
        ;

        $formModifier = function (FormInterface $form, Type $type = null) {
            $breeds = null === $type ? array() : $type->getBreeds();

            $form->add('breed', EntityType::class, array(
                'class' => 'AppBundle:Breed',
                'choice_label' => 'name',
                'choices' => $breeds,
            ));
        };

        $builder->addEventListener(
            FormEvents::PRE_SET_DATA,
            function (FormEvent $event) use ($formModifier) {
                // this would be your entity
            $form = $event->getForm();
            $data = $event->getData();
            $type = $form->get('type')->getData();
            if (null === $data) {
                    return; // On sort de la fonction lorsque $data vaut null
                }

                $formModifier($form, $type);
            }
        );

         $builder->get('type')->addEventListener(
            FormEvents::POST_SUBMIT,
            function (FormEvent $event) use ($formModifier) {
                // It's important here to fetch $event->getForm()->getData(), as
                // $event->getData() will get you the client data (that is, the ID)
                $type = $event->getForm()->getData();

                // since we've added the listener to the child, we'll have to pass on
                // the parent to the callback functions!
                $formModifier($event->getForm()->getParent(), $type);
            }
        );


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
