<?php

namespace App\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class NoBadWords extends Constraint
{
    public $message = 'La description contient des mots inappropriés : "{{ value }}"';
}