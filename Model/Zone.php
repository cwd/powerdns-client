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

use Symfony\Component\Validator\Constraints as Assert;
use Cwd\PowerDNSClient\Validator\Constraints as DNSAssert;

class Zone
{
    const TYPE = 'Zone';
    const KIND_MASTER = 'Master';
    const KIND_SLAVE = 'Slave';
    const KIND_NATIVE = 'Native';

    /** @var string|null */
    private $id;
    /**
     * @var string
     * @Assert\NotBlank()
     * @DNSAssert\HasDotPostfix()
     */
    private $name;
    /**
     * @var string
     * @Assert\NotBlank()
     * @Assert\Choice(
     *    choices= {"Zone"}
     * )
     */
    private $type = self::TYPE;

    /** @var string|null */
    private $url;
    /**
     * @var string|null
     * @Assert\Choice(
     *   choices = {"Master", "Slave", "Native"}
     * )
     */
    private $kind;
    /** @var RRSet[] */
    private $rrsets = [];
    /** @var int|null */
    private $serial;
    /** @var int|null */
    private $notifiedSerial;
    /** @var string[] */
    private $masters = [];
    /** @var bool */
    private $dnssec = false;
    /** @var string|null */
    private $nsec3param;
    /** @var bool */
    private $nsec3narrow = false;
    /** @var bool */
    private $predisgned = false;
    /** @var string|null */
    private $soaEdit;
    /** @var string|null */
    private $soaEditApi;
    /** @var bool */
    private $apiRectify = false;
    /** @var string|null */
    private $zone;
    /** @var string|null */
    private $account;
    /** @var string[] */
    private $nameservers = [];

    /**
     * @return null|string
     */
    public function getId(): ?string
    {
        return $this->id;
    }

    /**
     * @param null|string $id
     * @return Zone
     */
    public function setId(?string $id): Zone
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return Zone
     */
    public function setName(string $name): Zone
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @param string $type
     * @return Zone
     */
    public function setType(string $type): Zone
    {
        $this->type = $type;
        return $this;
    }

    /**
     * @return null|string
     */
    public function getUrl(): ?string
    {
        return $this->url;
    }

    /**
     * @param null|string $url
     * @return Zone
     */
    public function setUrl(?string $url): Zone
    {
        $this->url = $url;
        return $this;
    }

    /**
     * @return null|string
     */
    public function getKind(): ?string
    {
        return $this->kind;
    }

    /**
     * @param null|string $kind
     * @return Zone
     */
    public function setKind(?string $kind): Zone
    {
        $this->kind = $kind;
        return $this;
    }

    /**
     * @return RRSet[]
     */
    public function getRrsets(): array
    {
        return $this->rrsets;
    }

    /**
     * @param RRSet[] $rrsets
     * @return Zone
     */
    public function setRrsets(array $rrsets): Zone
    {
        $this->rrsets = $rrsets;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getSerial(): ?int
    {
        return $this->serial;
    }

    /**
     * @param int|null $serial
     * @return Zone
     */
    public function setSerial(?int $serial): Zone
    {
        $this->serial = $serial;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getNotifiedSerial(): ?int
    {
        return $this->notifiedSerial;
    }

    /**
     * @param int|null $notifiedSerial
     * @return Zone
     */
    public function setNotifiedSerial(?int $notifiedSerial): Zone
    {
        $this->notifiedSerial = $notifiedSerial;
        return $this;
    }

    /**
     * @return string[]
     */
    public function getMasters(): array
    {
        return $this->masters;
    }

    /**
     * @param string[] $masters
     * @return Zone
     */
    public function setMasters(array $masters): Zone
    {
        $this->masters = $masters;
        return $this;
    }

    /**
     * @return bool
     */
    public function isDnssec(): bool
    {
        return $this->dnssec;
    }

    /**
     * @param bool $dnssec
     * @return Zone
     */
    public function setDnssec(bool $dnssec): Zone
    {
        $this->dnssec = $dnssec;
        return $this;
    }

    /**
     * @return null|string
     */
    public function getNsec3param(): ?string
    {
        return $this->nsec3param;
    }

    /**
     * @param null|string $nsec3param
     * @return Zone
     */
    public function setNsec3param(?string $nsec3param): Zone
    {
        $this->nsec3param = $nsec3param;
        return $this;
    }

    /**
     * @return bool
     */
    public function isNsec3narrow(): bool
    {
        return $this->nsec3narrow;
    }

    /**
     * @param bool $nsec3narrow
     * @return Zone
     */
    public function setNsec3narrow(bool $nsec3narrow): Zone
    {
        $this->nsec3narrow = $nsec3narrow;
        return $this;
    }

    /**
     * @return bool
     */
    public function isPredisgned(): bool
    {
        return $this->predisgned;
    }

    /**
     * @param bool $predisgned
     * @return Zone
     */
    public function setPredisgned(bool $predisgned): Zone
    {
        $this->predisgned = $predisgned;
        return $this;
    }

    /**
     * @return null|string
     */
    public function getSoaEdit(): ?string
    {
        return $this->soaEdit;
    }

    /**
     * @param null|string $soaEdit
     * @return Zone
     */
    public function setSoaEdit(?string $soaEdit): Zone
    {
        $this->soaEdit = $soaEdit;
        return $this;
    }

    /**
     * @return null|string
     */
    public function getSoaEditApi(): ?string
    {
        return $this->soaEditApi;
    }

    /**
     * @param null|string $soaEditApi
     * @return Zone
     */
    public function setSoaEditApi(?string $soaEditApi): Zone
    {
        $this->soaEditApi = $soaEditApi;
        return $this;
    }

    /**
     * @return bool
     */
    public function isApiRectify(): bool
    {
        return $this->apiRectify;
    }

    /**
     * @param bool $apiRectify
     * @return Zone
     */
    public function setApiRectify(bool $apiRectify): Zone
    {
        $this->apiRectify = $apiRectify;
        return $this;
    }

    /**
     * @return null|string
     */
    public function getZone(): ?string
    {
        return $this->zone;
    }

    /**
     * @param null|string $zone
     * @return Zone
     */
    public function setZone(?string $zone): Zone
    {
        $this->zone = $zone;
        return $this;
    }

    /**
     * @return null|string
     */
    public function getAccount(): ?string
    {
        return $this->account;
    }

    /**
     * @param null|string $account
     * @return Zone
     */
    public function setAccount(?string $account): Zone
    {
        $this->account = $account;
        return $this;
    }

    /**
     * @return string[]
     */
    public function getNameservers(): array
    {
        return $this->nameservers;
    }

    /**
     * @param string[] $nameservers
     * @return Zone
     */
    public function setNameservers(array $nameservers): Zone
    {
        $this->nameservers = $nameservers;
        return $this;
    }
}
