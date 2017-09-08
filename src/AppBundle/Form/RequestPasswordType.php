<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use AppBundle\Entity\Password\RequestPassword;
use AppBundle\Entity\User;
use AppBundle\Repository\UserRepository;
use Symfony\Component\Form\FormInterface;
use Doctrine\ORM\EntityRepository;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\DependencyInjection\Container;

class RequestPasswordType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {     
        $builder->add('identifier', TextType::class, array('label' => 'Email ou Pseudo'));
        
        $builder->addEventListener(
            FormEvents::POST_SUBMIT,
            function (FormEvent $event) {
                $data = $event->getData();
                if (!$data instanceof RequestPassword) {
                    throw new \RuntimeException('RequestPassword instance required.');
                }
                $identifier = $data->getIdentifier();
                $form = $event->getForm();
                if (!$identifier || count($form->getErrors(true))) {
                    return;
                }
                
                // $user = $this->em->getRepository(User::class)->getUserByIdentifier($identifier);
               /* if (null == $user) {
                    $form->get('identifier')->addError(new FormError('Utilisateur non existant'));
                    return;
                } else {
                    $data->setUser($user);
                    if ($user->getIsAlreadyRequested() && null != $user->getConfirmationToken()) {
                        $form->get('identifier')->addError(new FormError('Demande de nouveau mot de passe déjà réalisée'));
                        return;
                    }
                } */
            }
        );
    }
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'AppBundle\Entity\Password\RequestPassword',
        ]);
    }
}