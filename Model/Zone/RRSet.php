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

namespace Cwd\PowerDNSClient\Model\Zone;

class RRSet
{
    /** @var string */
    private $name;
    /** @var string */
    private $type;
    /** @var int */
    private $ttl;
    /** @var string */
    private $changetype;
    /** @var Record[] */
    private $records = [];
    /** @var Comment[] */
    private $comments = [];

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     *
     * @return RRSet
     */
    public function setName(string $name): RRSet
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
     *
     * @return RRSet
     */
    public function setType(string $type): RRSet
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
     *
     * @return RRSet
     */
    public function setTtl(int $ttl): RRSet
    {
        $this->ttl = $ttl;

        return $this;
    }

    /**
     * @return string
     */
    public function getChangetype(): string
    {
        return $this->changetype;
    }

    /**
     * @param string $changetype
     *
     * @return RRSet
     */
    public function setChangetype(string $changetype): RRSet
    {
        $this->changetype = $changetype;

        return $this;
    }

    /**
     * @return Record[]
     */
    public function getRecords(): array
    {
        return $this->records;
    }

    /**
     * @param Record[] $records
     *
     * @return RRSet
     */
    public function setRecords(array $records): RRSet
    {
        $this->records = $records;

        return $this;
    }

    /**
     * @return Comment[]
     */
    public function getComments(): array
    {
        return $this->comments;
    }

    /**
     * @param Comment[] $comments
     *
     * @return RRSet
     */
    public function setComments(array $comments): RRSet
    {
        $this->comments = $comments;

        return $this;
    }
}
