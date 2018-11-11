<?php


namespace Cwd\PowerDNSClient\Model;


class CacheFlushResult
{
    /** @var integer */
    private $count;
    /** @var string */
    private $result;

    /**
     * @return int
     */
    public function getCount(): int
    {
        return $this->count;
    }

    /**
     * @param int $count
     * @return CacheFlushResult
     */
    public function setCount(int $count): CacheFlushResult
    {
        $this->count = $count;
        return $this;
    }

    /**
     * @return string
     */
    public function getResult(): string
    {
        return $this->result;
    }

    /**
     * @param string $result
     * @return CacheFlushResult
     */
    public function setResult(string $result): CacheFlushResult
    {
        $this->result = $result;
        return $this;
    }
}