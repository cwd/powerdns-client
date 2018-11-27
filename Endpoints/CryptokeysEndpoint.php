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
use Cwd\PowerDNSClient\Model\Cryptokey;

class CryptokeysEndpoint extends AbstractEndpoint
{
    use UriHelperTrait;

    private const ENDPOINT_LIST = 'servers/%s/zones/%s/cryptokeys';
    private const ENDPOINT_ELEMENT = 'servers/%s/zones/%s/cryptokeys/%s';

    private $zoneId;

    public function __construct(Client $client, $defaultServerId, $zoneId)
    {
        $this->zoneId = $zoneId;
        parent::__construct($client, $defaultServerId);
    }

    public function all($hydrationClass = Cryptokey::class): array
    {
        $uri = sprintf(self::ENDPOINT_LIST, $this->defaultServerId, $this->zoneId);

        return $this->getClient()->call(null, $uri, $hydrationClass, true, 'GET');
    }

    public function get($cryptokey, $hydrationClass = Cryptokey::class): ?Cryptokey
    {
        $cryptokeyId = $cryptokey;

        if ($cryptokey instanceof Cryptokey) {
            $cryptokeyId = $cryptokey->getId();
        }

        return $this->getClient()->call(null, $this->uriHelper($cryptokeyId), $hydrationClass, false, 'GET');
    }

    public function create(Cryptokey $cryptokey, $hydrationClass = Cryptokey::class): ?Cryptokey
    {
        $this->validateEntity($cryptokey, ['CREATE']);

        $uri = sprintf(self::ENDPOINT_LIST, $this->defaultServerId, $this->zoneId);
        $payload = $this->getClient()->getSerializer()->serialize($cryptokey, 'json');

        return $this->getClient()->call($payload, $uri, $hydrationClass, false, 'POST');
    }

    public function activate($cryptokey, $lacyLoad = true, $hydrationClass = Cryptokey::class): ?Cryptokey
    {
        return $this->setStatus(true, $cryptokey, $lacyLoad, $hydrationClass);
    }

    public function deactivate($cryptokey, $lacyLoad = true, $hydrationClass = Cryptokey::class): ?Cryptokey
    {
        return $this->setStatus(false, $cryptokey, $lacyLoad, $hydrationClass);
    }

    private function setStatus(bool $active, $cryptokey, $lacyLoad = true, $hydrationClass = Cryptokey::class): ?Cryptokey
    {
        $cryptokeyId = $cryptokey;
        if ($cryptokey instanceof Cryptokey) {
            $cryptokeyId = $cryptokey->getId();
        }

        $payload = $this->getClient()->getSerializer()->serialize(['active' => $active], 'json');

        $this->getClient()->call($payload, $this->uriHelper($cryptokeyId), null, false, 'PUT');

        if ($lacyLoad) {
            return $this->get($cryptokeyId, $hydrationClass);
        }

        return null;
    }

    public function delete($cryptokey): void
    {
        $cryptokeyId = $cryptokey;
        if ($cryptokey instanceof Cryptokey) {
            $cryptokeyId = $cryptokey->getId();
        }

        $this->getClient()->call(null, $this->uriHelper($cryptokeyId), null, false, 'DELETE');
    }
}
