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

use Cwd\PowerDNSClient\Model\Server;

class ServersEndpoint extends AbstractEndpoint
{
    const ENDPOINT = 'servers/%s';

    public function get(?string $serverId = null): Server
    {
        if (null === $serverId) {
            $serverId = $this->defaultServerId;
        }

        return $this->getClient()->call(null, sprintf('servers/%s', $serverId), Server::class, false, 'GET');
    }

    /**
     * @return Servers[]
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
}
