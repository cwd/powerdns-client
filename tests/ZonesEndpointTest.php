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

use Cwd\PowerDNSClient\Model\Zone;
use Cwd\PowerDNSClient\Validator\ValidationException;
use Webmozart\Assert\Assert;

class ZonesEndpointTest extends AbstractTest
{
    /**
     * This makes sure with have a clean startingpoint.
     */
    public function testCleanup()
    {
        $zones = $this->getClient()->zones()->all();
        foreach ($zones as $zone) {
            $this->getClient()->zones()->delete($zone);
        }

        $this->assertTrue(true);
    }

    public function testMissingDotPostfixValidation()
    {
        $this->expectException(ValidationException::class);

        $zone = (new Zone())
            ->setName('invalid.com')
            ->setKind(Zone::KIND_MASTER)
        ;

        return $this->getClient()->zones()->create($zone, true);
    }

    public function testMissingDotPostfixValidationMessages()
    {
        $zone = (new Zone())
            ->setName('invalid.com')
            ->setKind(Zone::KIND_MASTER)
        ;

        try {
            $this->getClient()->zones()->create($zone, true);
            $this->fail('No validation exception was thrown');
        } catch (ValidationException $e) {
            foreach ($e->getViolations() as $violation) {
                $this->assertTrue(!empty($violation->getMessage()));
            }
        }
    }

    public function testEmptyNameValidation()
    {
        $this->expectException(ValidationException::class);

        $zone = (new Zone())
            ->setKind(Zone::KIND_MASTER)
        ;

        return $this->getClient()->zones()->create($zone, true);
    }

    public function testWrongTypeValidation()
    {
        $this->expectException(ValidationException::class);

        $zone = (new Zone())
            ->setKind(Zone::KIND_MASTER)
            ->setType('whatever')
        ;

        return $this->getClient()->zones()->create($zone, true);
    }

    public function testCreate()
    {
        $zone = (new Zone())
            ->setName('example.com.')
            ->setKind(Zone::KIND_MASTER)
        ;

        $zone = $this->getClient()->zones()->create($zone, true);
        $this->assertNotEmpty($zone->getId());

        return $zone;
    }

    public function testCreateSlave()
    {
        $zone = (new Zone())
            ->setName('example-slave.com.')
            ->setKind(Zone::KIND_SLAVE)
        ;

        $zone = $this->getClient()->zones()->create($zone, true);
        $this->assertNotEmpty($zone->getId());

        return $zone;
    }

    public function testCreateExisting()
    {
        $this->expectException(\LogicException::class);

        $zone = (new Zone())
            ->setName('example.com.')
            ->setKind(Zone::KIND_MASTER)
        ;

        $this->getClient()->zones()->create($zone, true);
    }

    /**
     * @depends testCreate
     */
    public function testMissingHydrationClass(Zone $zone)
    {
        $this->expectException(\Exception::class);

        $this->getClient()->zones()->get($zone, 'NotExisting');
    }

    /**
     * @depends testCreate
     */
    public function testGetById(Zone $zone)
    {
        $newZone = $this->getClient()->zones()->get($zone->getId());

        $this->assertInstanceOf(Zone::class, $newZone);
        $this->assertEquals($zone, $newZone);
    }

    /**
     * @depends testCreate
     */
    public function testGetByObject(Zone $zone)
    {
        $newZone = $this->getClient()->zones()->get($zone);

        $this->assertInstanceOf(Zone::class, $newZone);
        $this->assertEquals($zone, $newZone);
    }

    /**
     * @depends testCreate
     */
    public function testAll()
    {
        $zones = $this->getClient()->zones()->all();

        $this->assertTrue(is_array($zones));
        $this->assertEquals(2, count($zones));
        Assert::allIsInstanceOf($zones, Zone::class);
    }

    /**
     * @depends testCreate
     * @
     */
    public function testAllWithNotFoundName()
    {
        $this->markTestSkipped('Does not work as documented');

        $zones = $this->getClient()->zones()->all('asdf.com');
        $this->assertTrue(is_array($zones));
        $this->assertEquals(0, count($zones));
        Assert::allIsInstanceOf($zones, Zone::class);
    }

    /**
     * @depends testCreate
     * @
     */
    public function testAllWithName()
    {
        $this->markTestSkipped('Does not work as documented');

        $zones = $this->getClient()->zones()->all('asdf.com');
        $this->assertTrue(is_array($zones));
        $this->assertEquals(1, count($zones));
        Assert::allIsInstanceOf($zones, Zone::class);
    }

    /**
     * @depends testCreateSlave
     */
    public function testDeleteZoneById(Zone $zone)
    {
        $this->getClient()->zones()->delete($zone->getId());

        $this->assertTrue(true, 'No Excpetion was thrown');

        $this->expectException(\Exception::class);
        $this->getClient()->zones()->get($zone->getId());
    }

    /**
     * @depends testCreate
     */
    public function testDeleteZoneByObject(Zone $zone)
    {
        $this->getClient()->zones()->delete($zone);
        $this->assertTrue(true, 'No Excpetion was thrown');

        $this->expectException(\Exception::class);
        $this->getClient()->zones()->get($zone->getId());
    }

    public function testNoMoreZones()
    {
        $zones = $this->getClient()->zones()->all();
        $this->assertEquals(0, count($zones));
    }

    public function testRemoveNotExistingZone()
    {
        $this->expectException(\LogicException::class);
        $this->getClient()->zones()->delete('notcreatedyet.com.');
    }
}
