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

use Cwd\PowerDNSClient\Model\Zone;

class ZonesEndpoint extends AbstractEndpoint
{
    const ENDPOINT_LIST = 'servers/%s/zones';
    const ENDPOINT_ELEMENT = 'servers/%s/zones/%s';

    public function update(Zone $zone, $lazyLoad = false, $hydrationClass = Zone::class): ?Zone
    {
        $this->validateEntity($zone, ['UPDATE']);

        //$zone->setRrsets([]);

        $payload = $this->getClient()->getSerializer()->serialize($zone, 'json', ['groups' => ['REPLACE']]);

        $this->getClient()->call($payload, sprintf(self::ENDPOINT_ELEMENT, $this->defaultServerId, $zone->getId()), null, false, 'PUT');

        if ($lazyLoad) {
            return $this->get($zone->getId(), $hydrationClass);
        }

        return null;
    }

    public function updateRRSets(Zone $zone, $lazyLoad = false, $hydrationClass = Zone::class): ?Zone
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
     * @param string|Zone $zoneId
     */
    public function delete($zoneId)
    {
        if ($zoneId instanceof Zone) {
            $zoneId = $zoneId->getId();
        }

        $this->getClient()->call(null, sprintf(self::ENDPOINT_ELEMENT, $this->defaultServerId, $zoneId), null, false, 'DELETE');
    }

    public function create(Zone $zone, $rrsets = true, $hydrationClass = Zone::class): Zone
    {
        $rrsets = ($rrsets) ? 'true' : 'false';

        $this->validateEntity($zone, ['CREATE']);

        $payload = $this->getClient()->getSerializer()->serialize($zone, 'json', ['groups' => ['CREATE']]);

        return $this->getClient()->call($payload, sprintf(self::ENDPOINT_LIST, $this->defaultServerId), $hydrationClass, false, 'POST', ['rrsets' => $rrsets]);
    }

    /**
     * @param string|Zone $zoneId
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
     * @return Servers[]
     *
     * @throws \Http\Client\Exception
     */
    public function all($zoneName = null, $hydrationClass = Zone::class): array
    {
        $queryParams = [];
        if (null !== $zoneName) {
            $queryParams['zone'] = $zoneName;
        }

        return $this->getClient()->call(null, sprintf(self::ENDPOINT_LIST, $this->defaultServerId), $hydrationClass, true, 'GET', $queryParams);
    }

    public function axfrRetrieve($zoneId)
    {
        if ($zoneId instanceof Zone) {
            $zoneId = $zoneId->getId();
        }

        $this->getClient()->call(null, sprintf(self::ENDPOINT_ELEMENT, $this->defaultServerId, $zoneId).'/axfr-retrieve', null, false, 'PUT');
    }

    public function notify($zoneId)
    {
        if ($zoneId instanceof Zone) {
            $zoneId = $zoneId->getId();
        }

        $this->getClient()->call(null, sprintf(self::ENDPOINT_ELEMENT, $this->defaultServerId, $zoneId).'/notify', null, false, 'PUT');
    }

    public function export($zoneId)
    {
        if ($zoneId instanceof Zone) {
            $zoneId = $zoneId->getId();
        }

        return $this->getClient()->call(null, sprintf(self::ENDPOINT_ELEMENT, $this->defaultServerId, $zoneId).'/export', null, false, 'GET');
    }

    public function check($zoneId)
    {
        if ($zoneId instanceof Zone) {
            $zoneId = $zoneId->getId();
        }

        return $this->getClient()->call(null, sprintf(self::ENDPOINT_ELEMENT, $this->defaultServerId, $zoneId).'/check', null, false, 'GET');
    }

    public function rectify($zoneId)
    {
        if ($zoneId instanceof Zone) {
            $zoneId = $zoneId->getId();
        }

        return $this->getClient()->call(null, sprintf(self::ENDPOINT_ELEMENT, $this->defaultServerId, $zoneId).'/rectify', null, false, 'PUT');
    }
}
