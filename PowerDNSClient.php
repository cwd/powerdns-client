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

namespace Cwd\PowerDNSClient;

use Cwd\PowerDNSClient\Endpoints\CryptokeysEndpoint;
use Cwd\PowerDNSClient\Endpoints\MetadataEndpoint;
use Cwd\PowerDNSClient\Endpoints\ServersEndpoint;
use Cwd\PowerDNSClient\Endpoints\ZonesEndpoint;
use Cwd\PowerDNSClient\Model\Zone;

class PowerDNSClient
{
    /** @var Client */
    private $client;

    /** @var ServersEndpoint */
    private $serversEndpoint;

    /** @var ZonesEndpoint */
    private $zonesEndpoint;

    /** @var CryptokeysEndpoint */
    private $cryptokeysEndpoint;

    /** @var string */
    private $defaultServerId = 'localhost';

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function metadata($zone): MetadataEndpoint
    {
        $zoneId = $zone;

        if ($zone instanceof Zone) {
            $zoneId = $zone->getId();
        }

        return new MetadataEndpoint($this->getClient(), $this->getDefaultServerId(), $zoneId);
    }

    public function servers(): ServersEndpoint
    {
        if (null === $this->serversEndpoint) {
            $this->serversEndpoint = new ServersEndpoint($this->getClient(), $this->getDefaultServerId());
        }

        return $this->serversEndpoint;
    }

    public function zones(): ZonesEndpoint
    {
        if (null === $this->zonesEndpoint) {
            $this->zonesEndpoint = new ZonesEndpoint($this->getClient(), $this->getDefaultServerId());
        }

        return $this->zonesEndpoint;
    }

    public function cryptokeys($zone): CryptokeysEndpoint
    {
        $zoneId = $zone;

        if ($zone instanceof Zone) {
            $zoneId = $zone->getId();
        }

        return new CryptokeysEndpoint($this->getClient(), $this->getDefaultServerId(), $zoneId);
    }

    public function getClient(): Client
    {
        return $this->client;
    }

    public function getDefaultServerId(): string
    {
        return $this->defaultServerId;
    }

    public function setDefaultServerId(string $defaultServerId): void
    {
        $this->defaultServerId = $defaultServerId;
    }
}
