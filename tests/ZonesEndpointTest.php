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

use Cwd\PowerDNSClient\Model\SearchResult;
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

    public function testWrongRRSetType()
    {
        $zone = (new Zone())
            ->setName('example.com.')
            ->setKind(Zone::KIND_MASTER)
            ->addRrset(
                (new Zone\RRSet())->setName('www.example.com.')
                    ->setType('WRONG')
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
        ;

        $this->expectException(ValidationException::class);
        $this->getClient()->zones()->create($zone, true);
    }

    public function testCreate()
    {
        $zone = (new Zone())
            ->setName('example.com.')
            ->setKind(Zone::KIND_MASTER)
            ->addRrset(
                (new Zone\RRSet())->setName('www.example.com.')
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
            ->addRrset((new Zone\RRSet())->setName('delete.example.com.')
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

        return $zone;
    }

    public function testCreateSlave()
    {
        $zone = (new Zone())
            ->setName('example-slave.com.')
            ->setKind(Zone::KIND_SLAVE)
            ->setMasters(['127.0.0.2'])
        ;

        $zone = $this->getClient()->zones()->create($zone, true);
        $this->assertNotEmpty($zone->getId());

        return $zone;
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
     * @param Zone $zone
     * @depends testCreate
     */
    public function testUpdateWithLazyLoad(Zone $zone)
    {
        $this->assertEmpty($zone->getAccount());
        $zone->setAccount('Max Mustermann');

        // Add new RRSet
        $zone->addRrset((new Zone\RRSet())->setChangetype(Zone\RRSet::TYPE_CREATE)
            ->setName('www3.example.com.')
            ->setType('A')
            ->setTtl(3600)
            ->addRecord((new Zone\Record())->setContent('127.0.0.3'))
        );

        $lazyLoadZone = $this->getClient()->zones()->update($zone, true);

        $zone = $this->getClient()->zones()->get($zone->getId());
        $this->assertEquals('Max Mustermann', $zone->getAccount());
        $this->assertEquals($zone, $lazyLoadZone);

        $found = false;
        foreach ($zone->getRrsets() as $recordset) {
            if ($recordset->getName('www3.example.com.')) {
                $found = true;
            }
        }

        if (!$found) {
            $this->fail('www3.example.com. not found');
        }
    }

    /**
     * @param Zone $zone
     * @depends testCreate
     */
    public function testUpdate(Zone $zone)
    {
        $zone->setAccount('Max Mustermann');

        // Add new RRSet
        $zone->addRrset((new Zone\RRSet())->setChangetype(Zone\RRSet::TYPE_CREATE)
            ->setName('www3.example.com.')
            ->setType('A')
            ->setTtl(3600)
            ->addRecord((new Zone\Record())->setContent('127.0.0.3'))
        );

        $this->getClient()->zones()->update($zone, false);

        $zone = $this->getClient()->zones()->get($zone->getId());
        $this->assertEquals('Max Mustermann', $zone->getAccount());

        $found = false;
        foreach ($zone->getRrsets() as $recordset) {
            if ($recordset->getName('www3.example.com.')) {
                $found = true;
            }
        }

        if (!$found) {
            $this->fail('www3.example.com. not found');
        }
    }

    /**
     * @param Zone $zone
     * @depends testCreate
     */
    public function testUpdateRRSetsWithLazyLoad(Zone $zone)
    {
        /** @var Zone\RRSet $recordSet */
        foreach ($zone->getRrsets() as &$recordSet) {
            if ('A' != $recordSet->getType()) {
                continue;
            }

            if ('delete.example.com.' == $recordSet->getName()) {
                $recordSet->setChangetype(Zone\RRSet::TYPE_DELETE);
            } elseif ('www.example.com.' == $recordSet->getName()) {
                /** @var Zone\Record $record */
                $record = current($recordSet->getRecords());
                $record->setContent('127.0.0.2');
                $recordSet->setChangetype(Zone\RRSet::TYPE_REPLACE);
            }
        }

        // Add new RRSet
        $zone->addRrset((new Zone\RRSet())->setChangetype(Zone\RRSet::TYPE_CREATE)
                                          ->setName('www2.example.com.')
                                          ->setType('A')
                                          ->setTtl(3600)
                                          ->addRecord((new Zone\Record())->setContent('127.0.0.2'))
        );

        $lazyLoadZone = $this->getClient()->zones()->updateRRSets($zone, true);
        $zone = $this->getClient()->zones()->get($zone->getId());
        $this->assertEquals($lazyLoadZone, $zone);

        $foundWWW2 = $foundWWW = false;

        /** @var Zone\RRSet $rrset */
        foreach ($zone->getRrsets() as $rrset) {
            if ('www2.example.com.' == $rrset->getName()) {
                $foundWWW2 = true;
                $record = current($rrset->getRecords());
                $this->assertEquals('127.0.0.2', $record->getContent());
            } elseif ('www.example.com.' == $rrset->getName()) {
                $foundWWW = true;
                $this->assertEquals(1, count($rrset->getRecords()));
                /** @var Record $record */
                $record = current($rrset->getRecords());
                $this->assertEquals('127.0.0.2', $record->getContent());
            } elseif ('delete.example.com.' == $rrset->getName()) {
                $this->fail('delete.example.com. not deleted!');
            }
        }

        if (!$foundWWW2) {
            $this->fail('Created record set www2.example.com not found');
        }

        if (!$foundWWW) {
            $this->fail('Changed record set www.example.com not found');
        }
    }

    /**
     * @param Zone $zone
     * @depends testCreate
     */
    public function testUpdateRRSets(Zone $zone)
    {
        /** @var Zone\RRSet $recordSet */
        foreach ($zone->getRrsets() as &$recordSet) {
            if ('A' != $recordSet->getType()) {
                continue;
            }

            if ('delete.example.com.' == $recordSet->getName()) {
                $recordSet->setChangetype(Zone\RRSet::TYPE_DELETE);
            } elseif ('www.example.com.' == $recordSet->getName()) {
                /** @var Zone\Record $record */
                $record = current($recordSet->getRecords());
                $record->setContent('127.0.0.2');
                $recordSet->setChangetype(Zone\RRSet::TYPE_REPLACE);
            }
        }

        // Add new RRSet
        $zone->addRrset((new Zone\RRSet())->setChangetype(Zone\RRSet::TYPE_CREATE)
            ->setName('www2.example.com.')
            ->setType('A')
            ->setTtl(3600)
            ->addRecord((new Zone\Record())->setContent('127.0.0.2'))
        );

        $this->getClient()->zones()->updateRRSets($zone, false);
        $zone = $this->getClient()->zones()->get($zone->getId());

        $foundWWW2 = $foundWWW = false;

        /** @var Zone\RRSet $rrset */
        foreach ($zone->getRrsets() as $rrset) {
            if ('www2.example.com.' == $rrset->getName()) {
                $foundWWW2 = true;
                $record = current($rrset->getRecords());
                $this->assertEquals('127.0.0.2', $record->getContent());
            } elseif ('www.example.com.' == $rrset->getName()) {
                $foundWWW = true;
                $this->assertEquals(1, count($rrset->getRecords()));
                /** @var Record $record */
                $record = current($rrset->getRecords());
                $this->assertEquals('127.0.0.2', $record->getContent());
            } elseif ('delete.example.com.' == $rrset->getName()) {
                $this->fail('delete.example.com. not deleted!');
            }
        }

        if (!$foundWWW2) {
            $this->fail('Created record set www2.example.com not found');
        }

        if (!$foundWWW) {
            $this->fail('Changed record set www.example.com not found');
        }
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
        $this->markAsRisky('Does not work as documented');
        //$this->getClient()->getClient()->setDebug(true);

        $zones = $this->getClient()->zones()->all('example.com.');
        $this->assertTrue(is_array($zones));
        //$this->assertEquals(0, count($zones));
        Assert::allIsInstanceOf($zones, Zone::class);

        //$this->getClient()->getClient()->setDebug(false);
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
     * @depends testCreate
     */
    public function testAxfrRetrieveFailsOnMaster(Zone $zone)
    {
        $this->expectException(\LogicException::class);
        $this->getClient()->zones()->axfrRetrieve($zone);
    }

    /**
     * @depends testCreateSlave
     */
    public function testAxfrRetrieve(Zone $zone)
    {
        $result = $this->getClient()->zones()->axfrRetrieve($zone);
        $this->assertNull($result);
    }

    /**
     * @depends testCreate
     */
    public function testNotify(Zone $zone)
    {
        $result = $this->getClient()->zones()->notify($zone);
        $this->assertNull($result);
        $this->markAsRisky();
    }

    /**
     * @depends testCreate
     */
    public function testExportZone(Zone $zone)
    {
        $result = $this->getClient()->zones()->export($zone);
        $this->assertEmpty($result);
        $this->markAsRisky();
    }

    /**
     * @depends testCreate
     */
    public function testCheck(Zone $zone)
    {
        $this->expectException(\LogicException::class);
        $result = $this->getClient()->zones()->check($zone);
        $this->markAsRisky();
    }

    /**
     * @depends testCreateSlave
     */
    public function testRectifyFailsonSlave(Zone $zone)
    {
        $this->expectException(\LogicException::class);
        $result = $this->getClient()->zones()->rectify($zone);
    }

    public function testRectifyOnDNSSecEnabled()
    {
        $this->markTestSkipped('not implemented yet');
    }

    /**
     * @depends testCreate
     */
    public function testSearch()
    {
        $result = $this->getClient()->zones()->search('example*');
        $this->assertCount(3, $result);
        Assert::allIsInstanceOf($result, SearchResult::class);
    }

    /**
     * @depends testCreate
     */
    public function testSearchMax()
    {
        $result = $this->getClient()->zones()->search('example*', 1);
        $this->assertCount(2, $result);
        Assert::allIsInstanceOf($result, SearchResult::class);
    }

    /**
     * @depends testCreate
     */
    public function testSearchSpecific()
    {
        $result = $this->getClient()->zones()->search('example.com');
        $this->assertCount(2, $result);
    }

    /**
     * @depends testCreate
     */
    public function testSearchEmpty()
    {
        $result = $this->getClient()->zones()->search('foobar*', 2);
        $this->assertCount(0, $result);
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
