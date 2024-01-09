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

use Cwd\PowerDNSClient\Model\Zone;
use Webmozart\Assert\Assert;

/**
 * @group performance
 */
class ZonesPerformanceTest extends AbstractTest
{
    private $domainTLD = [
        '.com.',
        '.net.',
        '.at.',
        '.de.',
        '.hosting.',
        '.wien.',
        '.cc.',
        '.gmbh.',
    ];

    private $createdCount = 0;

    private $domains = [];

    public function setUp(): void
    {
        $domains = [];

        for ($i = 0; $i < 100; ++$i) {
            $domains[] = $this->generateRandomString(12).$this->domainTLD[rand(0, count($this->domainTLD) - 1)];
        }
        array_unique($domains);

        foreach ($domains as $domain) {
            $zone = $this->createZone($domain);

            $this->getClient()->zones()->create($zone);
        }

        $this->createdCount = count($domains);
        $this->domains = $domains;
    }

    public function tearDown(): void
    {
        foreach ($this->domains as $domain) {
            $this->getClient()->zones()->delete($domain);
        }
    }

    public function testGetAll()
    {
        $zones = $this->getClient()->zones()->all();
        $this->assertCount($this->createdCount, $zones);
        Assert::allIsInstanceOf($zones, Zone::class);
    }

    public function testGetOne()
    {
        $domain = current($this->domains);
        $zone = $this->getClient()->zones()->get($domain);
        $this->assertInstanceOf(Zone::class, $zone);
        $this->assertCount(2, $zone->getRrsets());
    }

    private function createZone($domain): Zone
    {
        $zone = (new Zone())
            ->setName($domain)
            ->setKind(Zone\ZoneKind::MASTER)
            ->addRrset(
                (new Zone\RRSet())->setName('www.'.$domain)
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
            ->addRrset((new Zone\RRSet())->setName('delete.'.$domain)
                ->setType('A')
                ->setTtl(3600)
                ->addRecord(
                    (new Zone\Record())->setContent('127.0.0.1')
                        ->setDisabled(false)
                )
            )
        ;

        return $zone;
    }

    private function generateRandomString($length = 10)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; ++$i) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }

        return $randomString;
    }
}
