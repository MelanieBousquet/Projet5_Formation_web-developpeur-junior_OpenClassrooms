<?php

namespace AppBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */

class EmailOrPseudoUser extends Constraint
{
    public $message = 'L\'identifiant donné ne correspond à aucun compte existant.';
    
    public function validatedBy()
    {
        return EmailOrPseudoUserValidator::class;
    }
}

