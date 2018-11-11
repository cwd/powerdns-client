<?php

/*
 * This file is part of datamolino client.
 *
 * (c) 2018 cwd.at GmbH <office@cwd.at>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Cwd\PowerDNSClient\Endpoints;

use Cwd\PowerDNSClient\Client;

abstract class AbstractEndpoint
{
    private $client;
    protected $defaultServerId;

    public function __construct(Client $client, $defaultServerId)
    {
        $this->client = $client;
        $this->defaultServerId = $defaultServerId;
    }

    public function getClient(): Client
    {
        return $this->client;
    }
}
