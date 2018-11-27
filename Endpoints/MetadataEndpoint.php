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

use Cwd\PowerDNSClient\Client;
use Cwd\PowerDNSClient\Model\Metadata;

class MetadataEndpoint extends AbstractEndpoint
{
    use UriHelperTrait;

    private const ENDPOINT_LIST = 'servers/%s/zones/%s/metadata';
    private const ENDPOINT_ELEMENT = 'servers/%s/zones/%s/metadata/%s';

    private $zoneId;

    public function __construct(Client $client, $defaultServerId, $zoneId)
    {
        $this->zoneId = $zoneId;
        parent::__construct($client, $defaultServerId);
    }

    /**
     * @param string $hydrationClass
     *
     * @return Metadata[]
     *
     * @throws \Http\Client\Exception
     */
    public function all($hydrationClass = Metadata::class): array
    {
        $uri = sprintf(self::ENDPOINT_LIST, $this->defaultServerId, $this->zoneId);

        return $this->getClient()->call(null, $uri, $hydrationClass, true, 'GET');
    }

    /**
     * @param string $kind
     * @param string $hydrationClass
     *
     * @return Metadata
     *
     * @throws \Http\Client\Exception
     */
    public function get(string $kind, string $hydrationClass = Metadata::class): ?Metadata
    {
        return $this->getClient()->call(null, $this->uriHelper($kind), $hydrationClass, false, 'GET');
    }

    /**
     * @param Metadata $metadata
     * @param bool     $lacyLoad
     * @param string   $hydrationClass
     *
     * @return Metadata|null
     *
     * @throws \Http\Client\Exception
     */
    public function create(Metadata $metadata, $lacyLoad = true, $hydrationClass = Metadata::class): ?Metadata
    {
        $this->validateEntity($metadata, ['CREATE']);

        $uri = sprintf(self::ENDPOINT_LIST, $this->defaultServerId, $this->zoneId);
        $payload = $this->getClient()->getSerializer()->serialize($metadata, 'json');
        $this->getClient()->call($payload, $uri, null, false, 'POST');

        if ($lacyLoad) {
            return $this->get($metadata->getKind(), $hydrationClass);
        }

        return null;
    }

    /**
     * @param Metadata $metadata
     * @param bool     $lacyLoad
     * @param string   $hydrationClass
     *
     * @return Metadata|null
     *
     * @throws \Http\Client\Exception
     */
    public function update(Metadata $metadata, $lacyLoad = true, string $hydrationClass = Metadata::class): ?Metadata
    {
        $this->validateEntity($metadata, ['UPDATE']);
        $payload = $this->getClient()->getSerializer()->serialize($metadata, 'json');

        $this->getClient()->call($payload, $this->uriHelper($metadata->getKind()), null, false, 'PUT');

        if ($lacyLoad) {
            return $this->get($metadata->getKind(), $hydrationClass);
        }

        return null;
    }

    /**
     * @param Metadata|string $metadata
     *
     * @throws \Http\Client\Exception
     */
    public function delete($metadata): void
    {
        $kind = $metadata;
        if ($metadata instanceof Metadata) {
            $kind = $metadata->getKind();
        }
        $this->getClient()->call(null, $this->uriHelper($kind), null, false, 'DELETE');
    }
}
