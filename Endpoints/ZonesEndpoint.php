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

    /**
     * @param string|Zone $zoneId
     */
    public function delete($zoneId)
    {
        if ($zoneId instanceOf Zone) {
            $zoneId = $zoneId->getId();
        }

        $this->getClient()->call(null, sprintf(self::ENDPOINT_ELEMENT, $this->defaultServerId, $zoneId), null, false, 'DELETE');
    }


    public function create(Zone $zone, $rrsets = true, $hydrationClass = Zone::Class): Zone
    {
        $rrsets = ($rrsets) ? 'true' : 'false';

        $this->validateEntity($zone);

        $payload = $this->getClient()->getSerializer()->serialize($zone, 'json');

        return $this->getClient()->call($payload, sprintf(self::ENDPOINT_LIST, $this->defaultServerId), $hydrationClass, false, 'POST', ['rrsets' => $rrsets]);
    }

    /**
     * @param string|Zone $zoneId
     * @return Zone
     * @throws \Http\Client\Exception
     */
    public function get($zoneId, $hydrationClass = Zone::Class): Zone
    {
        if ($zoneId instanceOf Zone) {
            $zoneId = $zoneId->getId();
        }

        return $this->getClient()->call(null, sprintf(self::ENDPOINT_ELEMENT, $this->defaultServerId, $zoneId), $hydrationClass, false, 'GET');
    }

    /**
     * @return Servers[]
     *
     * @throws \Http\Client\Exception
     */
    public function all($zoneName = null, $hydrationClass = Zone::Class): array
    {
        $queryParams = [];
        if ($zoneName !== null) {
            $queryParams['zone'] = $zoneName;
        }

        return $this->getClient()->call(null, sprintf(self::ENDPOINT_LIST, $this->defaultServerId), $hydrationClass, true, 'GET', $queryParams);
    }

}
