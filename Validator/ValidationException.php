<?php

/*
 * This file is part of the CwdPowerDNS Client
 *
 * (c) 2018 cwd.at GmbH <office@cwd.at>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

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
