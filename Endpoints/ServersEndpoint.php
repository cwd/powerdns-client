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

namespace Cwd\PowerDNSClient\Endpoints;

use Cwd\PowerDNSClient\Model\CacheFlushResult;
use Cwd\PowerDNSClient\Model\Config;
use Cwd\PowerDNSClient\Model\Server;

class ServersEndpoint extends AbstractEndpoint
{
    private const ENDPOINT = 'servers/%s';

    /**
     * @param null|string $serverId
     *
     * @return Server
     *
     * @throws \Http\Client\Exception
     */
    public function get(?string $serverId = null): Server
    {
        if (null === $serverId) {
            $serverId = $this->defaultServerId;
        }

        return $this->getClient()->call(null, sprintf('servers/%s', $serverId), Server::class, false, 'GET');
    }

    /**
     * @return Server[]
     *
     * @throws \Http\Client\Exception
     */
    public function all(): array
    {
        return $this->getClient()->call(null, 'servers', Server::class, true, 'GET');
    }

    /**
     * @return array
     *
     * @throws \Http\Client\Exception
     */
    public function statistics(): array
    {
        // Result is different - denormalize by hand
        return $this->getClient()->call(null, sprintf(self::ENDPOINT, $this->defaultServerId).'/statistics', null, false, 'GET');
    }

    public function cacheFlush(string $domain): CacheFlushResult
    {
        return $this->getClient()->call(null, sprintf(self::ENDPOINT.'/cache/flush', $this->defaultServerId), CacheFlushResult::class, false, 'PUT', ['domain' => $domain]);
    }

    /**
     * @return Config[]
     *
     * @throws \Http\Client\Exception
     */
    public function config(): array
    {
        $uri = sprintf(self::ENDPOINT, $this->defaultServerId).'/config';

        return $this->getClient()->call(null, $uri, Config::class, true, 'GET');
    }
}
