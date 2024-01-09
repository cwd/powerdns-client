<?php

/*
 * This file is part of the CwdPowerDNS Client
 *
 * (c) 2024 cwd.at GmbH <office@cwd.at>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Cwd\PowerDNSClient\Tests;

use Cwd\PowerDNSClient\Client;
use Cwd\PowerDNSClient\PowerDNSClient;
use Doctrine\Common\Annotations\AnnotationReader;
use PHPUnit\Framework\TestCase;

abstract class AbstractTest extends TestCase
{
    /** @var PowerDNSClient */
    protected $pdnsClient;

    protected function getClient(): PowerDNSClient
    {
        if (null === $this->pdnsClient) {
            $client = new Client('http://powerdns', 'b60dc02889d7eb66a81f2f513ba7449d', null);
            $pdns = new PowerDNSClient($client);
            $pdns->setDefaultServerId('localhost');

            $this->pdnsClient = $pdns;
        }

        return $this->pdnsClient;
    }
}
