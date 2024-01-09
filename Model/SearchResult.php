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

class SearchResult
{

    protected ?string $content = null;
    protected bool $disabled = false;
    protected ?string $name = null;
    protected ?string $objectType = null;
    protected ?string $zoneId = null;
    protected ?string $zone = null;
    protected ?string $type = null;
    protected ?int $ttl = null;


    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): SearchResult
    {
        $this->content = $content;

        return $this;
    }


    public function isDisabled(): bool
    {
        return $this->disabled;
    }

    public function setDisabled(bool $disabled): SearchResult
    {
        $this->disabled = $disabled;

        return $this;
    }


    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): SearchResult
    {
        $this->name = $name;

        return $this;
    }


    public function getObjectType(): string
    {
        return $this->objectType;
    }

    public function setObjectType(string $objectType): SearchResult
    {
        $this->objectType = $objectType;

        return $this;
    }

    public function getZoneId(): string
    {
        return $this->zoneId;
    }

    public function setZoneId(string $zoneId): SearchResult
    {
        $this->zoneId = $zoneId;

        return $this;
    }

    public function getZone(): ?string
    {
        return $this->zone;
    }

    public function setZone(string $zone): SearchResult
    {
        $this->zone = $zone;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): SearchResult
    {
        $this->type = $type;

        return $this;
    }

    public function getTtl(): ?int
    {
        return $this->ttl;
    }

    public function setTtl(int $ttl): SearchResult
    {
        $this->ttl = $ttl;

        return $this;
    }
}
