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

use Cwd\PowerDNSClient\Model\Cryptokey;
use Cwd\PowerDNSClient\Model\Zone;
use Cwd\PowerDNSClient\Validator\ValidationException;

class CryptokeysEndpointTest extends AbstractTest
{
    private const ZONE = 'cryptokey.net.';

    public function setup()
    {
        $zone = (new Zone())
            ->setName(self::ZONE)
            ->setKind(Zone::KIND_MASTER)
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

        return $zone;
    }

    public function tearDown()
    {
        $this->getClient()->zones()->delete(self::ZONE);
    }

    public function testCreate()
    {
        $cryptokey = (new Cryptokey())
            ->setActive(false)
            ->setKeytype('ksk');

        $key = $this->getClient()->cryptokeys((new Zone())->setId(self::ZONE))->create($cryptokey);

        $this->assertFalse($key->isActive());

        foreach ($key->getDs() as $ds) {
            list($tag) = preg_split('/\s+/', $ds);
            $this->assertEquals($key->getTag(), $tag);
        }

        return $key;
    }

    public function testGet()
    {
        $key = $this->testCreate();
        $nkey = $this->getClient()->cryptokeys(self::ZONE)->get($key->getId());
        $this->assertEquals($key, $nkey);
    }

    public function testGetByObject()
    {
        $key = $this->testCreate();
        $nkey = $this->getClient()->cryptokeys(self::ZONE)->get($key);
        $this->assertEquals($key, $nkey);
    }

    public function testAll()
    {
        $nkeys = $this->getClient()->cryptokeys(self::ZONE)->all();
        $this->assertCount(0, $nkeys);

        $this->testCreate();
        $nkeys = $this->getClient()->cryptokeys(self::ZONE)->all();
        $this->assertGreaterThanOrEqual(1, count($nkeys));
    }

    public function testActivate()
    {
        $key = $this->testCreate();
        $this->assertFalse($key->isActive());

        $updated = $this->getClient()->cryptokeys(self::ZONE)->activate($key, true);
        $this->assertTrue($updated->isActive());
    }

    public function testActivateWithoutLacyLoad()
    {
        $key = $this->testCreate();
        $this->assertFalse($key->isActive());

        $updated = $this->getClient()->cryptokeys(self::ZONE)->activate($key, false);
        $this->assertNull($updated);
    }

    public function testCreateActive()
    {
        $cryptokey = (new Cryptokey())
            ->setActive(true)
            ->setKeytype('ksk');

        $key = $this->getClient()->cryptokeys(self::ZONE)->create($cryptokey);

        $this->assertTrue($key->isActive());

        foreach ($key->getDs() as $ds) {
            list($tag) = preg_split('/\s+/', $ds);
            $this->assertEquals($key->getTag(), $tag);
        }

        //dump($key);
        return $key;
    }

    public function testDeactivate()
    {
        $key = $this->testCreateActive();
        $this->assertTrue($key->isActive());

        $updated = $this->getClient()->cryptokeys(self::ZONE)->deactivate($key, true);
        $this->assertFalse($updated->isActive());
    }

    public function testDelete()
    {
        $key = $this->testCreate();
        $this->getClient()->cryptokeys(self::ZONE)->delete($key);

        $all = $this->getClient()->cryptokeys(self::ZONE)->all();
        $this->assertCount(0, $all);

        $this->expectException(\Exception::class);
        $this->getClient()->cryptokeys(self::ZONE)->get($key->getId());
    }

    public function testDeleteById()
    {
        $key = $this->testCreate();
        $this->getClient()->cryptokeys(self::ZONE)->delete($key->getId());

        $all = $this->getClient()->cryptokeys(self::ZONE)->all();
        $this->assertCount(0, $all);

        $this->expectException(\Exception::class);
        $this->getClient()->cryptokeys(self::ZONE)->get($key->getId());
    }

    public function testCreateWithMoreBitThanAllowed()
    {
        $cryptokey = (new Cryptokey())
            ->setActive(true)
            ->setKeytype('ksk')
            ->setBits(521)
        ;

        $this->expectException(\LogicException::class);
        $key = $this->getClient()->cryptokeys(self::ZONE)->create($cryptokey);
    }

    public function testCreateWithBitAndAlgorithm()
    {
        $cryptokey = (new Cryptokey())
            ->setActive(true)
            ->setKeytype('ksk')
            ->setAlgorithm('RSASHA1')
            ->setBits(512)
        ;

        try {
            $key = $this->getClient()->cryptokeys(self::ZONE)->create($cryptokey);
            $this->assertEquals($cryptokey->getBits(), $key->getBits());
        } catch (ValidationException $e) {
            $this->fail(print_r($e->getViolations(), true));
        }
    }

    public function testCreateWithUnknownAlgo()
    {
        $cryptokey = (new Cryptokey())
            ->setActive(true)
            ->setKeytype('ksk')
            ->setAlgorithm('foobar')
            ->setBits(512)
        ;

        $this->expectException(ValidationException::class);
        $this->getClient()->cryptokeys(self::ZONE)->create($cryptokey);
    }

    public function testCreateWithUnknownKeytype()
    {
        $cryptokey = (new Cryptokey())
            ->setActive(true)
            ->setKeytype('fff')
        ;

        $this->expectException(ValidationException::class);
        $this->getClient()->cryptokeys(self::ZONE)->create($cryptokey);
    }

    public function testCreateWithAlgoId()
    {
        foreach ([13, 14] as $algo) {
            $cryptokey = (new Cryptokey())
                ->setActive(true)
                ->setKeytype('zsk')
                ->setAlgorithm($algo);

            $key = $this->getClient()->cryptokeys(self::ZONE)->create($cryptokey);
            $this->assertInstanceOf(Cryptokey::class, $key);
        }
    }

    public function testCreateWithAlgoName()
    {
        foreach ([Cryptokey::VALID_ALGORITHMS[5], Cryptokey::VALID_ALGORITHMS[7]] as $algo) {
            $cryptokey = (new Cryptokey())
                ->setActive(true)
                ->setKeytype('zsk')
                ->setBits(256)
                ->setAlgorithm($algo);

            $key = $this->getClient()->cryptokeys(self::ZONE)->create($cryptokey);
            $this->assertInstanceOf(Cryptokey::class, $key);
        }
    }
}
