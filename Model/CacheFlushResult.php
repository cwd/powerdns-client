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

class CacheFlushResult
{
    /** @var int */
    protected $count;
    /** @var string */
    protected $result;

    /**
     * @return int
     */
    public function getCount(): int
    {
        return $this->count;
    }

    /**
     * @param int $count
     *
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
     *
     * @return CacheFlushResult
     */
    public function setResult(string $result): CacheFlushResult
    {
        $this->result = $result;

        return $this;
    }
}
