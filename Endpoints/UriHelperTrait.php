<?php

/*
 * This file is part of the CwdPowerDNS Client
 *
 * (c) 2024 cwd.at GmbH <office@cwd.at>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Cwd\PowerDNSClient\Endpoints;

trait UriHelperTrait
{
    protected function uriHelper($kind): string
    {
        return sprintf(self::ENDPOINT_ELEMENT, $this->defaultServerId, $this->zoneId, $kind);
    }
}
