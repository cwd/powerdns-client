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

class CacheFlushResult
{
    protected int $count;

    protected string $result;

    public function getCount(): int
    {
        return $this->count;
    }

    public function setCount(int $count): CacheFlushResult
    {
        $this->count = $count;

        return $this;
    }

    public function getResult(): string
    {
        return $this->result;
    }

    public function setResult(string $result): CacheFlushResult
    {
        $this->result = $result;

        return $this;
    }
}
