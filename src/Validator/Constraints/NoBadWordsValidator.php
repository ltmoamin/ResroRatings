<?php



namespace App\Validator\Constraints;


use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
class NoBadWordsValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint)
    {
        // Liste des mots interdits
        $badWords = ['mot1', 'mot2', 'mot3']; // Ajoutez ici les mots interdits

        // Vérifie si la description contient des mots interdits
        foreach ($badWords as $word) {
            if (stripos($value, $word) !== false) {
                $this->context->buildViolation($constraint->message)
                    ->setParameter('{{ value }}', $value)
                    ->addViolation();
                break;
            }
        }
    }
}