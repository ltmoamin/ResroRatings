<?php

namespace App\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class ValidType extends Constraint
{

        public $message = 'L etat doit être en_attente ou en_cours.';


    public function validatedBy()
    {
        return \get_class($this).'Validator';
    }
}