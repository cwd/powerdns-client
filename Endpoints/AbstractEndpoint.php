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

namespace Cwd\PowerDNSClient\Endpoints;

use Cwd\PowerDNSClient\Client;
use Cwd\PowerDNSClient\Validator\ValidationException;
use Symfony\Component\Validator\Validation;

abstract class AbstractEndpoint
{
    private $client;
    protected $defaultServerId;
    protected $validator;

    public function __construct(Client $client, $defaultServerId)
    {
        $this->client = $client;
        $this->defaultServerId = $defaultServerId;
        $this->validator = Validation::createValidatorBuilder()
                            ->enableAnnotationMapping()
                            ->getValidator();
    }

    public function validateEntity($entity)
    {
        $violations = $this->validator->validate($entity);

        if (count($violations) > 0) {
            throw new ValidationException(
                sprintf('Entity %s does not validate to spezification', get_class($entity)),
                0,
                null,
                $violations
            );
        }

        return true;
    }

    public function getClient(): Client
    {
        return $this->client;
    }
}
