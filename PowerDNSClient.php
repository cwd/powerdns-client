<?php
/*
 * This file is part of powerdns client.
 *
 * (c) 2018 cwd.at GmbH <office@cwd.at>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Cwd\PowerDNSClient;

use Cwd\PowerDNSClient\Endpoints\ServersEndpoint;

class PowerDNSClient
{
    /** @var Client */
    private $client;

    /** @var ServersEndpoint */
    private $serversEndpoint;

    /** @var string  */
    private $defaultServerId = 'localhost';

    public function __construct(?Client $client = null)
    {
        if (null === $client) {
            $this->client = new Client('http://localhost', 'changeme');
        } else {
            $this->client = $client;
        }
    }

    public function servers(): ServersEndpoint
    {
        if (null === $this->serversEndpoint) {
            $this->serversEndpoint = new ServersEndpoint($this->getClient(), $this->getDefaultServerId());
        }

        return $this->serversEndpoint;
    }

    /**
     * @return Client
     */
    public function getClient(): Client
    {
        return $this->client;
    }

    /**
     * @return string
     */
    public function getDefaultServerId(): string
    {
        return $this->defaultServerId;
    }

    /**
     * @param string $defaultServerId
     */
    public function setDefaultServerId(string $defaultServerId): void
    {
        $this->defaultServerId = $defaultServerId;
    }
}
