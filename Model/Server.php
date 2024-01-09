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

namespace Cwd\PowerDNSClient\Model;

class Server
{
    protected string $type = 'Server';
    protected string $id;
    protected string $daemonType;
    protected string $version;
    protected string $url;
    protected string $configUrl;
    protected string $zonesUrl;

    public function getType(): string
    {
        return $this->type;
    }

    public function setType(string $type): Server
    {
        $this->type = $type;

        return $this;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function setId(string $id): Server
    {
        $this->id = $id;

        return $this;
    }

    public function getDaemonType(): string
    {
        return $this->daemonType;
    }

    public function setDaemonType(string $daemonType): Server
    {
        $this->daemonType = $daemonType;

        return $this;
    }

    public function getVersion(): string
    {
        return $this->version;
    }

    public function setVersion(string $version): Server
    {
        $this->version = $version;

        return $this;
    }

    public function getUrl(): string
    {
        return $this->url;
    }

    public function setUrl(string $url): Server
    {
        $this->url = $url;

        return $this;
    }

    public function getConfigUrl(): string
    {
        return $this->configUrl;
    }

    public function setConfigUrl(string $configUrl): Server
    {
        $this->configUrl = $configUrl;

        return $this;
    }

    public function getZonesUrl(): string
    {
        return $this->zonesUrl;
    }

    public function setZonesUrl(string $zonesUrl): Server
    {
        $this->zonesUrl = $zonesUrl;

        return $this;
    }
}
