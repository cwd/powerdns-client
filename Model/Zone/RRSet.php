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

use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use Cwd\PowerDNSClient\Validator\Constraints as DNSAssert;

class RRSet
{
    public const TYPE_REPLACE = 'REPLACE';
    public const TYPE_DELETE = 'DELETE';
    public const TYPE_CREATE = 'REPLACE'; // Yes this is by design!

    /**
     * @var string
     * @Assert\NotBlank(groups={"CREATE", "UPDATE"})
     * @DNSAssert\HasDotPostfix(groups={"CREATE", "UPDATE"})
     *
     * @Groups({"REPLACE", "CREATE", "DELETE"})
     */
    private $name;

    /**
     * @var string
     * @Assert\NotBlank(groups={"CREATE", "UPDATE"})
     * @Assert\Choice(
     *    groups={"CREATE", "UPDATE"},
     *    choices={
     *     "A", "AAAA", "AFSDB", "ALIAS", "CAA", "CERT", "CDNSKEY", "CDS", "CNAME", "DNSKEY", "DNAME", "DS", "HINFO",
     *     "KEY", "LOC", "MX", "NAPTR", "NS", "NSEC, NSEC3, NSEC3PARAM", "OPENPGPKEY", "PTR", "RP", "RRSIG", "SOA",
     *     "SPF", "SSHFP", "SRV", "TKEY, TSIG", "TLSA", "SMIMEA", "TXT", "URI"
     *    }
     * )
     * @Groups({"REPLACE", "CREATE", "DELETE"})
     */
    private $type;
    /**
     * @var int
     * @Groups({"REPLACE", "CREATE"})
     */
    private $ttl;

    /**
     * @var string
     * @Assert\Choice(
     *    choices={"REPLACE", "DELETE", "CREATE"},
     *    groups={"CREATE", "UPDATE"}
     * )
     * @Groups({"REPLACE", "DELETE"})
     */
    private $changetype;

    /**
     * @var Record[]
     * @Assert\Valid(groups={"CREATE", "UPDATE"})
     * @Groups({"REPLACE", "CREATE"})
     */
    private $records = [];

    /**
     * @var Comment[]
     * @Assert\Valid(groups={"CREATE", "UPDATE"})
     * @Groups({"REPLACE", "CREATE"})
     */
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
    public function getChangetype(): ?string
    {
        return $this->changetype;
    }

    /**
     * @param string $changetype
     *
     * @return RRSet
     */
    public function setChangetype(?string $changetype): RRSet
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

    public function addRecord(Record $record): RRSet
    {
        $this->records[] = $record;

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
        \Webmozart\Assert\Assert::allIsInstanceOf($comments, Comment::class);
        $this->comments = $comments;

        return $this;
    }

    public function addComment(Comment $comment): RRSet
    {
        $this->comments[] = $comment;

        return $this;
    }
}
