<?php


namespace Cwd\PowerDNSClient\Model;


class Server
{
    /** @var string */
    private $type;
    /** @var string */
    private $id;
    /** @var string */
    private $daemonType;
    /** @var string */
    private $version;
    /** @var string */
    private $url;
    /** @var string */
    private $configUrl;
    /** @var string */
    private $zonesUrl;

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @param string $type
     * @return Server
     */
    public function setType(string $type): Server
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
     * @return Server
     */
    public function setId(string $id): Server
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return string
     */
    public function getDaemonType(): string
    {
        return $this->daemonType;
    }

    /**
     * @param string $daemonType
     * @return Server
     */
    public function setDaemonType(string $daemonType): Server
    {
        $this->daemonType = $daemonType;
        return $this;
    }

    /**
     * @return string
     */
    public function getVersion(): string
    {
        return $this->version;
    }

    /**
     * @param string $version
     * @return Server
     */
    public function setVersion(string $version): Server
    {
        $this->version = $version;
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
     * @return Server
     */
    public function setUrl(string $url): Server
    {
        $this->url = $url;
        return $this;
    }

    /**
     * @return string
     */
    public function getConfigUrl(): string
    {
        return $this->configUrl;
    }

    /**
     * @param string $configUrl
     * @return Server
     */
    public function setConfigUrl(string $configUrl): Server
    {
        $this->configUrl = $configUrl;
        return $this;
    }

    /**
     * @return string
     */
    public function getZonesUrl(): string
    {
        return $this->zonesUrl;
    }

    /**
     * @param string $zonesUrl
     * @return Server
     */
    public function setZonesUrl(string $zonesUrl): Server
    {
        $this->zonesUrl = $zonesUrl;
        return $this;
    }
}