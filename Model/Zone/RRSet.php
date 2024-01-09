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

    #[Groups(['REPLACE', 'CREATE', 'DELETE'])]
    #[Assert\NotBlank(groups: ['CREATE', 'UPDATE'])]
    #[DNSAssert\HasDotPostfix(groups: ['CREATE', 'UPDATE'])]
    protected ?string $name = null;

    #[Assert\Choice(groups: ['CREATE', 'UPDATE'], choices: [
     'A', 'AAAA', 'AFSDB', 'ALIAS', 'CAA', 'CERT', 'CDNSKEY', 'CDS', 'CNAME', 'DNSKEY', 'DNAME', 'DS', 'HINFO',
     'KEY', 'LOC', 'MX', 'NAPTR', 'NS', 'NSEC, NSEC3, NSEC3PARAM', 'OPENPGPKEY', 'PTR', 'RP', 'RRSIG', 'SOA',
     'SPF', 'SSHFP', 'SRV', 'TKEY, TSIG', 'TLSA', 'SMIMEA', 'TXT', 'URI'
    ])]
    #[Assert\NotBlank(groups: ['CREATE', 'UPDATE'])]
    #[Groups(['REPLACE', 'CREATE', 'DELETE'])]
    protected ?string $type = null;

    #[Groups(['REPLACE', 'CREATE'])]
    protected ?int $ttl = null;

    #[Assert\Choice(groups: ['CREATE', 'UPDATE'], choices: ['REPLACE', 'DELETE', 'CREATE'])]
    #[Groups(['REPLACE', 'DELETE'])]
    protected ?string $changetype = null;

    #[Assert\Valid(groups: ['CREATE', 'UPDATE'])]
    #[Groups(['REPLACE', 'DELETE'])]
    protected array $records = [];

    #[Assert\Valid(groups: ['CREATE', 'UPDATE'])]
    #[Groups(['REPLACE', 'CREATE'])]
    protected array $comments = [];

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): RRSet
    {
        $this->name = $name;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(?string $type): RRSet
    {
        $this->type = $type;

        return $this;
    }

    public function getTtl(): ?int
    {
        return $this->ttl;
    }

    public function setTtl(?int $ttl): RRSet
    {
        $this->ttl = $ttl;

        return $this;
    }

    public function getChangetype(): ?string
    {
        return $this->changetype;
    }

    public function setChangetype(?string $changetype): RRSet
    {
        $this->changetype = $changetype;

        return $this;
    }

    public function getRecords(): array
    {
        return $this->records;
    }

    /**
     * @param Record[] $records
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
