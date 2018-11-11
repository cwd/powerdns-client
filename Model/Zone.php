<?php


namespace Cwd\PowerDNSClient\Model;


class Zone
{
    /** @var string */
    private $id;
    /** @var string */
    private $name;
    /** @var string */
    private $type;
    /** @var string */
    private $url;
    /** @var string */
    private $kind;
    /** @var RRSet[] */
    private $rrsets = [];
    /** @var integer */
    private $serial;
    /** @var integer */
    private $notifiedSerial;
    /** @var string[] */
    private $masters = [];
    /** @var bool  */
    private $dnssec = false;
    /** @var string */
    private $nsec3param;
    /** @var bool  */
    private $nsec3narrow = false;
    /** @var bool  */
    private $predisgned = false;
    /** @var string */
    private $soaEdit;
    /** @var string */
    private $soaEditApi;
    /** @var bool  */
    private $apiRectify = false;
    /** @var string */
    private $zone;
    /** @var string */
    private $account;
    /** @var string[] */
    private $nameservers = [];

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @param string $id
     * @return Zone
     */
    public function setId(string $id): Zone
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
     * @return string
     */
    public function getUrl(): string
    {
        return $this->url;
    }

    /**
     * @param string $url
     * @return Zone
     */
    public function setUrl(string $url): Zone
    {
        $this->url = $url;
        return $this;
    }

    /**
     * @return string
     */
    public function getKind(): string
    {
        return $this->kind;
    }

    /**
     * @param string $kind
     * @return Zone
     */
    public function setKind(string $kind): Zone
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
     * @return int
     */
    public function getSerial(): int
    {
        return $this->serial;
    }

    /**
     * @param int $serial
     * @return Zone
     */
    public function setSerial(int $serial): Zone
    {
        $this->serial = $serial;
        return $this;
    }

    /**
     * @return int
     */
    public function getNotifiedSerial(): int
    {
        return $this->notifiedSerial;
    }

    /**
     * @param int $notifiedSerial
     * @return Zone
     */
    public function setNotifiedSerial(int $notifiedSerial): Zone
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
     * @return string
     */
    public function getNsec3param(): string
    {
        return $this->nsec3param;
    }

    /**
     * @param string $nsec3param
     * @return Zone
     */
    public function setNsec3param(string $nsec3param): Zone
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
     * @return string
     */
    public function getSoaEdit(): string
    {
        return $this->soaEdit;
    }

    /**
     * @param string $soaEdit
     * @return Zone
     */
    public function setSoaEdit(string $soaEdit): Zone
    {
        $this->soaEdit = $soaEdit;
        return $this;
    }

    /**
     * @return string
     */
    public function getSoaEditApi(): string
    {
        return $this->soaEditApi;
    }

    /**
     * @param string $soaEditApi
     * @return Zone
     */
    public function setSoaEditApi(string $soaEditApi): Zone
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
     * @return string
     */
    public function getZone(): string
    {
        return $this->zone;
    }

    /**
     * @param string $zone
     * @return Zone
     */
    public function setZone(string $zone): Zone
    {
        $this->zone = $zone;
        return $this;
    }

    /**
     * @return string
     */
    public function getAccount(): string
    {
        return $this->account;
    }

    /**
     * @param string $account
     * @return Zone
     */
    public function setAccount(string $account): Zone
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