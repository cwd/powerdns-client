<?php


namespace Cwd\PowerDNSClient\Model;


class Cryptokey
{
    /** @var string */
    private $type;
    /** @var string */
    private $id;
    /** @var string */
    private $keytype;
    /** @var bool  */
    private $active = false;
    /** @var string */
    private $dnskey;
    /** @var string[] */
    private $ds = [];
    /** @var string */
    private $privatekey;
    /** @var string */
    private $algorithm;
    /** @var integer */
    private $bits;

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @param string $type
     * @return Cryptokey
     */
    public function setType(string $type): Cryptokey
    {
        $this->type = $type;
        return $this;
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @param string $id
     * @return Cryptokey
     */
    public function setId(string $id): Cryptokey
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return string
     */
    public function getKeytype(): string
    {
        return $this->keytype;
    }

    /**
     * @param string $keytype
     * @return Cryptokey
     */
    public function setKeytype(string $keytype): Cryptokey
    {
        $this->keytype = $keytype;
        return $this;
    }

    /**
     * @return bool
     */
    public function isActive(): bool
    {
        return $this->active;
    }

    /**
     * @param bool $active
     * @return Cryptokey
     */
    public function setActive(bool $active): Cryptokey
    {
        $this->active = $active;
        return $this;
    }

    /**
     * @return string
     */
    public function getDnskey(): string
    {
        return $this->dnskey;
    }

    /**
     * @param string $dnskey
     * @return Cryptokey
     */
    public function setDnskey(string $dnskey): Cryptokey
    {
        $this->dnskey = $dnskey;
        return $this;
    }

    /**
     * @return string[]
     */
    public function getDs(): array
    {
        return $this->ds;
    }

    /**
     * @param string[] $ds
     * @return Cryptokey
     */
    public function setDs(array $ds): Cryptokey
    {
        $this->ds = $ds;
        return $this;
    }

    /**
     * @return string
     */
    public function getPrivatekey(): string
    {
        return $this->privatekey;
    }

    /**
     * @param string $privatekey
     * @return Cryptokey
     */
    public function setPrivatekey(string $privatekey): Cryptokey
    {
        $this->privatekey = $privatekey;
        return $this;
    }

    /**
     * @return string
     */
    public function getAlgorithm(): string
    {
        return $this->algorithm;
    }

    /**
     * @param string $algorithm
     * @return Cryptokey
     */
    public function setAlgorithm(string $algorithm): Cryptokey
    {
        $this->algorithm = $algorithm;
        return $this;
    }

    /**
     * @return int
     */
    public function getBits(): int
    {
        return $this->bits;
    }

    /**
     * @param int $bits
     * @return Cryptokey
     */
    public function setBits(int $bits): Cryptokey
    {
        $this->bits = $bits;
        return $this;
    }
}