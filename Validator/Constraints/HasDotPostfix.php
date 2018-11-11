<?php


namespace Cwd\PowerDNSClient\Validator\Constraints;


use Cwd\PowerDNSClient\Validator\ZoneNameValidator;
use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class HasDotPostfix extends Constraint
{
    public $message = 'The zone name "{{ string }}" must end with "."';

    public function validatedBy()
    {
        return ZoneNameValidator::class;
    }
}