<?php


namespace Cwd\PowerDNSClient\Model;


class SearchResult
{
    /** @var string */
    private $content;
    /** @var bool  */
    private $disabled = false;
    /** @var string */
    private $name;
    /** @var string */
    private $objectType;
    /** @var string */
    private $zoneId;
    /** @var string */
    private $zone;
    /** @var string */
    private $type;
    /** @var integer */
    private $ttl;

    /**
     * @return string
     */
    public function getContent(): string
    {
        return $this->content;
    }

    /**
     * @param string $content
     * @return SearchResult
     */
    public function setContent(string $content): SearchResult
    {
        $this->content = $content;
        return $this;
    }

    /**
     * @return bool
     */
    public function isDisabled(): bool
    {
        return $this->disabled;
    }

    /**
     * @param bool $disabled
     * @return SearchResult
     */
    public function setDisabled(bool $disabled): SearchResult
    {
        $this->disabled = $disabled;
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
     * @return SearchResult
     */
    public function setName(string $name): SearchResult
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return string
     */
    public function getObjectType(): string
    {
        return $this->objectType;
    }

    /**
     * @param string $objectType
     * @return SearchResult
     */
    public function setObjectType(string $objectType): SearchResult
    {
        $this->objectType = $objectType;
        return $this;
    }

    /**
     * @return string
     */
    public function getZoneId(): string
    {
        return $this->zoneId;
    }

    /**
     * @param string $zoneId
     * @return SearchResult
     */
    public function setZoneId(string $zoneId): SearchResult
    {
        $this->zoneId = $zoneId;
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
     * @return SearchResult
     */
    public function setZone(string $zone): SearchResult
    {
        $this->zone = $zone;
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
     * @return SearchResult
     */
    public function setType(string $type): SearchResult
    {
        $this->type = $type;
        return $this;
    }

    /**
     * @return int
     */
    public function getTtl(): int
    {
        return $this->ttl;
    }

    /**
     * @param int $ttl
     * @return SearchResult
     */
    public function setTtl(int $ttl): SearchResult
    {
        $this->ttl = $ttl;
        return $this;
    }
}