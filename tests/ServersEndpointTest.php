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

namespace Cwd\PowerDNSClient\Tests;

use Cwd\PowerDNSClient\Model\Server;

class ServersEndpointTest extends AbstractTest
{
    public function testStatistics()
    {
        $stats = $this->getClient()->servers()->statistics();

        $this->assertTrue(is_array($stats));
        $this->assertGreaterThan(1, count($stats));
        $this->assertTrue(isset($stats[0]->name));
    }

    public function testAll()
    {
        $servers = $this->getClient()->servers()->all();
        $this->assertTrue(is_array($servers));
        $this->assertGreaterThan(0, count($servers));
    }

    public function testGet()
    {
        $server = $this->getClient()->servers()->get();
        $this->assertInstanceOf(Server::class, $server);
        $this->assertEquals('localhost', $server->getId());
    }

    public function testGetUnknown()
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Error on request 404:');
        $server = $this->getClient()->servers()->get('foobar');
    }
}
