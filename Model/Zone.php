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

namespace Cwd\PowerDNSClient\Model;

use Cwd\PowerDNSClient\Model\Zone\RRSet;
use Cwd\PowerDNSClient\Model\Zone\ZoneKind;
use Cwd\PowerDNSClient\Validator\Constraints as DNSAssert;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

class Zone
{
    protected const TYPE = 'Zone';
    /** @deprecated - Use ZoneKind::class Enum */
    public const KIND_MASTER = 'Master';
    /** @deprecated - Use ZoneKind::class Enum */
    public const KIND_SLAVE = 'Slave';
    /** @deprecated - Use ZoneKind::class Enum */
    public const KIND_NATIVE = 'Native';

    #[Groups(['CREATE', 'DELETE'])]
    protected ?string $id = null;

    #[Groups(['CREATE', 'DELETE'])]
    #[DNSAssert\HasDotPostfix(groups: ['CREATE'])]
    #[Assert\NotBlank(groups: ['CREATE'])]
    protected ?string $name = null;

    #[Groups(['REPLACE', 'CREATE', 'DELETE'])]
    #[Assert\NotBlank]
    #[Assert\Choice(groups: ['CREATE', 'UPDATE'], choices: ['Zone'])]
    protected string $type = self::TYPE;

    #[Groups('CREATE', 'DELETE')]
    protected ?string $url = null;

    #[Groups(['REPLACE', 'CREATE', 'DELETE'])]
    #[Assert\Type(groups: ['CREATE', 'UPDATE'], type: ZoneKind::class)]
    protected ?ZoneKind $kind = null;

    #[Groups(['REPLACE', 'CREATE', 'DELETE'])]
    #[Assert\Valid(groups: ['CREATE', 'UPDATE'])]
    protected array $rrsets = [];

    protected ?int $serial = null;
    protected ?int $notifiedSerial = null;

    #[Groups(['REPLACE', 'CREATE', 'DELETE'])]
    protected array $masters = [];

    #[Groups(['REPLACE', 'CREATE', 'DELETE'])]
    protected bool $dnssec = false;

    #[Groups(['REPLACE', 'CREATE', 'DELETE'])]
    protected ?string $nsec3param = null;

    #[Groups(['REPLACE', 'CREATE', 'DELETE'])]
    protected bool $nsec3narrow = false;

    #[Groups(['REPLACE', 'CREATE', 'DELETE'])]
    protected bool $predisgned = false;

    #[Groups(['REPLACE', 'CREATE', 'DELETE'])]
    protected ?string $soaEdit = null;

    #[Groups(['REPLACE', 'CREATE', 'DELETE'])]
    protected ?string $soaEditApi = null;

    #[Groups(['REPLACE', 'CREATE', 'DELETE'])]
    protected bool $apiRectify = false;

    #[Groups(['REPLACE', 'CREATE', 'DELETE'])]
    protected ?string $zone = null;

    #[Groups(['REPLACE', 'CREATE', 'DELETE'])]
    protected ?string $account;

    #[Groups(['REPLACE', 'CREATE', 'DELETE'])]
    protected array $nameservers = [];

    public function getId(): ?string
    {
        return $this->id;
    }

    public function setId(?string $id): Zone
    {
        $this->id = $id;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): Zone
    {
        $this->name = $name;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(?string $type): Zone
    {
        $this->type = $type;

        return $this;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(?string $url): Zone
    {
        $this->url = $url;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getKind(): ?ZoneKind
    {
        return $this->kind;
    }

    /**
     * @param string|null $kind
     */
    public function setKind(?ZoneKind $kind): Zone
    {
        $this->kind = $kind;

        return $this;
    }

    /**
     * @return array<RRSet>
     */
    public function getRrsets(): array
    {
        return $this->rrsets;
    }

    public function setRrsets(array $rrsets): Zone
    {
        $this->rrsets = $rrsets;

        return $this;
    }

    public function addRrset(RRSet $rrset): Zone
    {
        $this->rrsets[] = $rrset;

        return $this;
    }

    public function getSerial(): ?int
    {
        return $this->serial;
    }

    public function setSerial(?int $serial): Zone
    {
        $this->serial = $serial;

        return $this;
    }

    public function getNotifiedSerial(): ?int
    {
        return $this->notifiedSerial;
    }

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
     */
    public function setMasters(array $masters): Zone
    {
        $this->masters = $masters;

        return $this;
    }

    public function isDnssec(): bool
    {
        return $this->dnssec;
    }

    public function setDnssec(bool $dnssec): Zone
    {
        $this->dnssec = $dnssec;

        return $this;
    }

    public function getNsec3param(): ?string
    {
        return $this->nsec3param;
    }

    public function setNsec3param(?string $nsec3param): Zone
    {
        $this->nsec3param = $nsec3param;

        return $this;
    }

    public function isNsec3narrow(): bool
    {
        return $this->nsec3narrow;
    }

    public function setNsec3narrow(bool $nsec3narrow): Zone
    {
        $this->nsec3narrow = $nsec3narrow;

        return $this;
    }

    public function isPredisgned(): bool
    {
        return $this->predisgned;
    }

    public function setPredisgned(bool $predisgned): Zone
    {
        $this->predisgned = $predisgned;

        return $this;
    }

    public function getSoaEdit(): ?string
    {
        return $this->soaEdit;
    }

    public function setSoaEdit(?string $soaEdit): Zone
    {
        $this->soaEdit = $soaEdit;

        return $this;
    }

    public function getSoaEditApi(): ?string
    {
        return $this->soaEditApi;
    }

    public function setSoaEditApi(?string $soaEditApi): Zone
    {
        $this->soaEditApi = $soaEditApi;

        return $this;
    }

    public function isApiRectify(): bool
    {
        return $this->apiRectify;
    }

    public function setApiRectify(bool $apiRectify): Zone
    {
        $this->apiRectify = $apiRectify;

        return $this;
    }

    public function getZone(): ?string
    {
        return $this->zone;
    }

    public function setZone(?string $zone): Zone
    {
        $this->zone = $zone;

        return $this;
    }

    public function getAccount(): ?string
    {
        return $this->account;
    }

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
     */
    public function setNameservers(array $nameservers): Zone
    {
        $this->nameservers = $nameservers;

        return $this;
    }
}
