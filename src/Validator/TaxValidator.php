<?php

namespace App\Validator;

use App\Service\CalculatePriceService\CalculatePriceService;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use UnexpectedValueException;

/**
 * @Annotation
 * @Target({"PROPERTY", "METHOD", "ANNOTATION"})
 *
 * @author Jan Schädlich <jan.schaedlich@sensiolabs.de>
 */
#[\Attribute(\Attribute::TARGET_PROPERTY | \Attribute::TARGET_METHOD | \Attribute::IS_REPEATABLE)]
class TaxValidator extends ConstraintValidator
{
    public function validate(mixed $value, Constraint $constraint): void
    {
        if (!is_string($value) || '' === $value) {
            throw new UnexpectedValueException($value, 'string');
        }

        foreach (CalculatePriceService::PATTERN_MAP_TAX as $pattern) {
            if (preg_match($pattern, $value, $matches)) {
             return;
            }
        }
        $this->context->buildViolation($constraint->message)
            ->setParameter('{{ string }}', $value)
            ->addViolation();
    }
}
