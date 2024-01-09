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

use Cwd\PowerDNSClient\Model\CacheFlushResult;
use Cwd\PowerDNSClient\Model\Config;
use Cwd\PowerDNSClient\Model\Server;
use Cwd\PowerDNSClient\Model\Zone;
use Webmozart\Assert\Assert;

class ServersEndpointTest extends AbstractTest
{
    public const ZONE = 'metadata.net.';

    public function setup(): void
    {
        $zone = (new Zone())
            ->setName(self::ZONE)
            ->setKind(Zone\ZoneKind::MASTER)
            ->addRrset(
                (new Zone\RRSet())->setName('www.'.self::ZONE)
                    ->setType('A')
                    ->setTtl(3600)
                    ->addRecord(
                        (new Zone\Record())->setContent('127.0.0.1')
                            ->setDisabled(false)
                    )
                    ->addComment(
                        (new Zone\Comment())->setContent('Test Test')
                            ->setAccount('Max Mustermann')
                    )
            )
            ->addRrset((new Zone\RRSet())->setName('delete.'.self::ZONE)
                ->setType('A')
                ->setTtl(3600)
                ->addRecord(
                    (new Zone\Record())->setContent('127.0.0.1')
                        ->setDisabled(false)
                )
                ->addComment((new Zone\Comment())->setContent('test')->setAccount('Maxi'))
            )
        ;

        $zone = $this->getClient()->zones()->create($zone, true);
        $this->assertNotEmpty($zone->getId());
    }

    public function tearDown(): void
    {
        $this->getClient()->zones()->delete(self::ZONE);
    }

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

    public function testCacheFlush()
    {
        $result = $this->getClient()->servers()->cacheFlush(self::ZONE);
        $this->assertInstanceOf(CacheFlushResult::class, $result);
    }

    public function testCacheFlushNoneCanonical()
    {
        $this->expectException(\LogicException::class);
        $this->getClient()->servers()->cacheFlush('hostname');
    }

    public function testConfigAll()
    {
        $result = $this->getClient()->servers()->config();
        $this->assertGreaterThan(100, count($result));
        Assert::allIsInstanceOf($result, Config::class);
    }
}
