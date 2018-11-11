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
