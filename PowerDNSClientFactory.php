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
    private $config = [];
    private $reader;

    const SLAVE = 'slave';
    const MASTER = 'master';

    /**
     * @var array<string,PowerDNSClient>
     */
    private $clients = [];

    public function __construct(array $config = [], Reader $reader)
    {
        $this->config = $config;
        $this->reader = $reader;
    }

    public function getClient(string $name): PowerDNSClient
    {
        if (isset($this->clients[$name]) && $this->clients[$name] instanceof PowerDNSClient) {
            return $this->client[$name];
        }

        foreach ($this->config as $configName => $config) {
            if ($name === $configName) {
                $client = new Client($config['uri'], $config['api_key'], $this->reader);
                $this->client[$name] = new PowerDNSClient($client);

                return $this->client($name);
            }
        }

        throw new \RuntimeException(sprintf('No configuration for "%s% is found', $name));
    }

    /**
     * @return array<PowerDNSClient>
     */
    public function getSlaves(): array
    {
        $clients = [];

        foreach ($this->config as $configName => $config) {
            if (isset($config['type']) && $config['type'] == self::SLAVE) {
                $clients[] = $this->getClient($configName);
            }
        }

        return $clients;
    }


    /**
     * @param string $uri
     * @param string $apiKey
     * @param string|null $defaultServer
     * @param Reader $reader
     * @return PowerDNSClient
     */
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