<?php


namespace Cwd\PowerDNSClient\Validator;


use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\ConstraintViolationListInterface;

class ValidationException extends \DomainException
{
    /** @var ConstraintViolationList */
    protected $violations;

    public function __construct($message, $code = 0, $previous = null, ConstraintViolationListInterface $violations)
    {
        parent::__construct($message, $code, $previous);
        $this->violations = $violations;
    }

    public function getViolations()
    {
        return $this->violations;
    }
}