<?php

namespace AppBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Doctrine\ORM\EntityManager;

class EmailOrPseudoUserValidator extends ConstraintValidator
{
    public function __construct(EntityManager $em) {
        $this->em = $em;
    }
    
    public function validate($value, Constraint $constraint)
    {
        $user = $this->em->getRepository('AppBundle:User')->getUserByEmailOrPseudo($value);
        if (null == $user) {
            $this->context->buildViolation($constraint->message)
                ->addViolation();
        }
    }
}