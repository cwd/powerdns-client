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

use Cwd\PowerDNSClient\Model\Metadata;
use Cwd\PowerDNSClient\Model\Zone;
use Cwd\PowerDNSClient\Validator\ValidationException;
use Webmozart\Assert\Assert;

class MetadataEndpointTest extends AbstractTest
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

    public function testAll()
    {
        $metadatas = $this->getClient()->metadata(self::ZONE)->all();
        $this->assertGreaterThanOrEqual(2, count($metadatas));
        Assert::allIsInstanceOf($metadatas, Metadata::class);
    }

    public function testMetadataWithObject()
    {
        $metadatas = $this->getClient()->metadata((new Zone())->setId(self::ZONE))->all();
        $this->assertGreaterThanOrEqual(2, count($metadatas));
        Assert::allIsInstanceOf($metadatas, Metadata::class);
    }

    public function testGet()
    {
        $metadata = $this->getClient()->metadata(self::ZONE)->get('ALLOW-AXFR-FROM');
        $this->assertInstanceOf(Metadata::class, $metadata);
        $this->assertEquals(0, count($metadata->getMetadata()));
    }

    public function testGetUnknownTypeThrowsException()
    {
        $this->expectException(\LogicException::class);
        $metadata = $this->getClient()->metadata(self::ZONE)->get('FOOBAR');
    }

    public function testGetKindWithXPrefixDontThrowException()
    {
        $metadata = $this->getClient()->metadata(self::ZONE)->get('X-FOOBAR');
        $this->assertInstanceOf(Metadata::class, $metadata);
    }

    public function testCreate()
    {
        $metadatas[] = (new Metadata())->setKind('ALLOW-AXFR-FROM')->setMetadata(['127.0.0.2']);
        $metadatas[] = (new Metadata())->setKind('ALLOW-AXFR-FROM')->setMetadata(['127.0.0.3']);
        $metadatas[] = (new Metadata())->setKind('X-MyVeryOwn')->setMetadata(['foobar']);

        $result = $this->getClient()->metadata(self::ZONE)->create($metadatas[0], true);
        $this->assertInstanceOf(Metadata::class, $result);
        $this->assertEquals(1, count($result->getMetadata()));

        $result = $this->getClient()->metadata(self::ZONE)->create($metadatas[1], true);
        $this->assertInstanceOf(Metadata::class, $result);
        $this->assertEquals(2, count($result->getMetadata()));

        $result = $this->getClient()->metadata(self::ZONE)->create($metadatas[2], true);
        $this->assertInstanceOf(Metadata::class, $result);
        $this->assertEquals(1, count($result->getMetadata()));
    }

    public function testCreateWithUnknownThrowsException()
    {
        $metadata = (new Metadata())->setKind('FOOBAR')->setMetadata(['127.0.0.2']);
        $this->expectException(ValidationException::class);
        $this->getClient()->metadata(self::ZONE)->create($metadata, false);
    }

    public function testCreateWithXPrefixIsValid()
    {
        $metadata = (new Metadata())->setKind('X-BAR')->setMetadata(['127.0.0.2']);
        try {
            $this->getClient()->metadata(self::ZONE)->create($metadata, false);
            $this->assertTrue(true, 'No Exception thrown');
        } catch (ValidationException $e) {
            $this->fail('Create with X-BAR failed');
        }
    }

    public function testUpdate()
    {
        $metadata = (new Metadata())->setKind('ALLOW-AXFR-FROM')->setMetadata(['127.0.0.10']);
        try {
            $this->getClient()->metadata(self::ZONE)->update($metadata, false);
            $this->assertTrue(true);
        } catch (\Exception $e) {
            $this->fail($e->getMessage());
        }

        $metadata = (new Metadata())->setKind('ALLOW-AXFR-FROM')->setMetadata(['127.0.0.10', '127.0.0.1']);
        $result = $this->getClient()->metadata(self::ZONE)->update($metadata, true);
        $this->assertInstanceOf(Metadata::class, $result);
        $this->assertEquals(2, count($result->getMetadata()));
    }

    public function testUpdateForbidden()
    {
        $metadata = (new Metadata())->setKind('PRESIGNED')->setMetadata([1]);

        $this->expectException(ValidationException::class);
        $this->getClient()->metadata(self::ZONE)->update($metadata, true);
    }

    public function testDelete()
    {
        $metadata = (new Metadata())->setKind('X-TEST')->setMetadata(['127.0.0.10', '127.0.0.1']);
        $metadata = $this->getClient()->metadata(self::ZONE)->create($metadata, true);
        $this->assertEquals(2, count($metadata->getMetadata()));

        $this->getClient()->metadata(self::ZONE)->delete($metadata);
        $metadata = $this->getClient()->metadata(self::ZONE)->get('X-TEST');
        $this->assertEquals(0, count($metadata->getMetadata()));
    }
}
