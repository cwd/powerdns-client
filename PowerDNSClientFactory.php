<?php
/*
 * This file is part of the Cwd PowerDNS Client
 *
 * (c) 2018 cwd.at GmbH <office@cwd.at>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Cwd\PowerDNSClient;

use Doctrine\Common\Annotations\Reader;

class PowerDNSClientFactory
{
    static public function createClient(string $uri, string $apiKey, ?string $defaultServer = null, Reader $reader): PowerDNSClient
    {
        $client = new Client($uri, $apiKey, null, $reader);
        $pdns = new PowerDNSClient($client);
        if ($defaultServer !== null) {
            $pdns->setDefaultServerId($defaultServer);
        }

        return $pdns;
    }
}