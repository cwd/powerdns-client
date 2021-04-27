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

use Cwd\PowerDNSClient\Model\SearchResult;
use Cwd\PowerDNSClient\Model\Zone;

class ZonesEndpoint extends AbstractEndpoint
{
    protected const ENDPOINT_LIST = 'servers/%s/zones';
    protected const ENDPOINT_ELEMENT = 'servers/%s/zones/%s';

    /**
     * @param Zone   $zone
     * @param bool   $lazyLoad
     * @param string $hydrationClass
     *
     * @return Zone|null
     *
     * @throws \Http\Client\Exception
     */
    public function update(Zone $zone, $lazyLoad = false, string $hydrationClass = Zone::class): ?Zone
    {
        $this->validateEntity($zone, ['UPDATE']);
        $payload = $this->getClient()->getSerializer()->serialize($zone, 'json', ['groups' => ['REPLACE']]);

        $this->getClient()->call($payload, sprintf(self::ENDPOINT_ELEMENT, $this->defaultServerId, $zone->getId()), null, false, 'PUT');

        if ($lazyLoad) {
            return $this->get($zone->getId(), $hydrationClass);
        }

        return null;
    }

    /**
     * @param Zone   $zone
     * @param bool   $lazyLoad
     * @param string $hydrationClass
     *
     * @return Zone|null
     *
     * @throws \Http\Client\Exception
     */
    public function updateRRSets(Zone $zone, bool $lazyLoad = false, string $hydrationClass = Zone::class): ?Zone
    {
        $this->validateEntity($zone, ['UPDATE']);

        // Remove RecordSets which are not changed
        $newSet = [];
        /** @var Zone\RRSet $rrset */
        foreach ($zone->getRrsets() as $rrset) {
            if (null !== $rrset->getChangetype()) {
                $newSet[] = $rrset;
            }
        }

        $payload = $this->getClient()->getSerializer()->serialize(['rrsets' => $newSet], 'json', ['groups' => ['REPLACE']]);

        $this->getClient()->call($payload, sprintf(self::ENDPOINT_ELEMENT, $this->defaultServerId, $zone->getId()), null, false, 'PATCH');

        if ($lazyLoad) {
            return $this->get($zone->getId(), $hydrationClass);
        }

        return null;
    }

    /**
     * @param int|Zone $zoneId
     *
     * @throws \Http\Client\Exception
     */
    public function delete($zoneId): void
    {
        if ($zoneId instanceof Zone) {
            $zoneId = $zoneId->getId();
        }

        $this->getClient()->call(null, sprintf(self::ENDPOINT_ELEMENT, $this->defaultServerId, $zoneId), null, false, 'DELETE');
    }

    /**
     * @param Zone   $zone
     * @param bool   $rrsets
     * @param string $hydrationClass
     *
     * @return Zone
     *
     * @throws \Http\Client\Exception
     */
    public function create(Zone $zone, $rrsets = true, string $hydrationClass = Zone::class): Zone
    {
        $rrsets = $rrsets ? 'true' : 'false';

        $this->validateEntity($zone, ['CREATE']);

        $payload = $this->getClient()->getSerializer()->serialize($zone, 'json', ['groups' => ['CREATE']]);

        return $this->getClient()->call($payload, sprintf(self::ENDPOINT_LIST, $this->defaultServerId), $hydrationClass, false, 'POST');
    }

    /**
     * @param string|Zone $zoneId
     * @param string      $hydrationClass
     *
     * @return Zone
     *
     * @throws \Http\Client\Exception
     */
    public function get($zoneId, $hydrationClass = Zone::class): Zone
    {
        if ($zoneId instanceof Zone) {
            $zoneId = $zoneId->getId();
        }

        return $this->getClient()->call(null, sprintf(self::ENDPOINT_ELEMENT, $this->defaultServerId, $zoneId), $hydrationClass, false, 'GET');
    }

    /**
     * @param string $zoneName
     * @param string $hydrationClass
     *
     * @return Zone[]
     *
     * @throws \Http\Client\Exception
     */
    public function all(?string $zoneName = null, string $hydrationClass = Zone::class): array
    {
        $queryParams = [];
        if (null !== $zoneName) {
            $queryParams['zone[]'] = $zoneName;
        }

        return $this->getClient()->call(null, sprintf(self::ENDPOINT_LIST, $this->defaultServerId), $hydrationClass, true, 'GET', $queryParams);
    }

    /**
     * @param int|Zone $zoneId
     *
     * @throws \Http\Client\Exception
     */
    public function axfrRetrieve($zoneId): void
    {
        if ($zoneId instanceof Zone) {
            $zoneId = $zoneId->getId();
        }

        $this->getClient()->call(null, sprintf(self::ENDPOINT_ELEMENT, $this->defaultServerId, $zoneId).'/axfr-retrieve', null, false, 'PUT');
    }

    /**
     * @param int|Zone $zoneId
     *
     * @throws \Http\Client\Exception
     */
    public function notify($zoneId): void
    {
        if ($zoneId instanceof Zone) {
            $zoneId = $zoneId->getId();
        }

        $this->getClient()->call(null, sprintf(self::ENDPOINT_ELEMENT, $this->defaultServerId, $zoneId).'/notify', null, false, 'PUT');
    }

    /**
     * @param int|Zone $zoneId
     *
     * @return string
     *
     * @throws \Http\Client\Exception
     */
    public function export($zoneId): ?string
    {
        if ($zoneId instanceof Zone) {
            $zoneId = $zoneId->getId();
        }

        return $this->getClient()->call(null, sprintf(self::ENDPOINT_ELEMENT, $this->defaultServerId, $zoneId).'/export', null, false, 'GET', [], false);
    }

    /**
     * @param int|Zone $zoneId
     *
     * @return string
     *
     * @throws \Http\Client\Exception
     */
    public function check($zoneId): string
    {
        if ($zoneId instanceof Zone) {
            $zoneId = $zoneId->getId();
        }

        return $this->getClient()->call(null, sprintf(self::ENDPOINT_ELEMENT, $this->defaultServerId, $zoneId).'/check', null, false, 'GET');
    }

    /**
     * @param int|Zone $zoneId
     *
     * @return string
     *
     * @throws \Http\Client\Exception
     */
    public function rectify($zoneId): string
    {
        if ($zoneId instanceof Zone) {
            $zoneId = $zoneId->getId();
        }

        return $this->getClient()->call(null, sprintf(self::ENDPOINT_ELEMENT, $this->defaultServerId, $zoneId).'/rectify', null, false, 'PUT');
    }

    public function search($term, $maxResults = 100)
    {
        $queryParams = [
            'q' => $term,
            'max' => $maxResults,
        ];

        return $this->getClient()->call(null, sprintf('servers/%s/search-data', $this->defaultServerId), SearchResult::class, true, 'GET', $queryParams);
    }
}
