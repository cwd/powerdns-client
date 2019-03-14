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

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

class Cryptokey
{
    protected const VALID_KEYTYPES = [
        'ksk',
        'zsk',
        'csk',
    ];

    // https://doc.powerdns.com/md/authoritative/dnssec/#supported-algorithms
    // https://github.com/operasoftware/dns-ui/issues/57#issuecomment-377553394
    public const VALID_ALGORITHMS = [
        1 => 'RSAMD5',
        2 => 'DH',
        3 => 'DSA',

        5 => 'RSASHA1',
        6 => 'DSA-NSEC3-SHA1',
        7 => 'RSASHA1-NSEC3-SHA1',
        8 => 'RSASHA256',

        10 => 'RSASHA512',

        12 => 'ECC-GOST',
        13 => 'ECDSAP256SHA256',
        14 => 'ECDSAP384SHA384',
        15 => 'ED25519',
        16 => 'ED448',
    ];

    /** @var string */
    protected $type = 'Cryptokey';

    /** @var int */
    protected $id;
    /**
     * @var string
     * @Assert\Choice(
     *   choices = {"ksk", "zsk", "csk"},
     *   groups={"CREATE"}
     * )
     */
    protected $keytype;

    /** @var bool */
    protected $active = false;
    /** @var string */
    protected $dnskey;
    /** @var string[] */
    protected $ds = [];
    /** @var string */
    protected $protectedkey;
    /** @var string */
    protected $algorithm;
    /** @var int */
    protected $bits;

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
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @param int $id
     *
     * @return Cryptokey
     */
    public function setId(int $id): Cryptokey
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return string
     */
    public function getKeytype(): ?string
    {
        return $this->keytype;
    }

    /**
     * @param string $keytype
     *
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
     *
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
    public function getDnskey(): ?string
    {
        return $this->dnskey;
    }

    /**
     * @param string $dnskey
     *
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
     *
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
    public function getprotectedkey(): ?string
    {
        return $this->protectedkey;
    }

    /**
     * @param string $protectedkey
     *
     * @return Cryptokey
     */
    public function setprotectedkey(string $protectedkey): Cryptokey
    {
        $this->protectedkey = $protectedkey;

        return $this;
    }

    /**
     * @return string|int|null
     */
    public function getAlgorithm()
    {
        return $this->algorithm;
    }

    /**
     * @param string|int $algorithm
     *
     * @return Cryptokey
     */
    public function setAlgorithm($algorithm): Cryptokey
    {
        $this->algorithm = $algorithm;

        return $this;
    }

    /**
     * @return int
     */
    public function getBits(): ?int
    {
        return $this->bits;
    }

    /**
     * @param int $bits
     *
     * @return Cryptokey
     */
    public function setBits(int $bits): Cryptokey
    {
        $this->bits = $bits;

        return $this;
    }

    /**
     * Reconstruct the DNSKEY RDATA wire format (https://tools.ietf.org/html/rfc4034#section-2.1)
     * by merging the flags, protocol, algorithm, and the base64-decoded key data
     * https://github.com/operasoftware/dns-ui/commit/35821799f7c2a2e17e9178612e24147dfe7c0867#diff-0d3376b053b1313ed26a84a0c61ef0f2R344
     * by Thomas Pike.
     *
     * @return string
     */
    public function getTag(): ?int
    {
        if (null === $this->dnskey) {
            return null;
        }

        list($flags, $protocol, $algorithm, $keydata) = preg_split('/\s+/', $this->dnskey);

        $wire_format = pack('nCC', $flags, $protocol, $algorithm).base64_decode($keydata);
        // Split data into (zero-indexed) array of bytes
        $keyvalues = array_values(unpack('C*', $wire_format));
        // Follow algorithm from RFC 4034 Appendix B (https://tools.ietf.org/html/rfc4034#appendix-B)
        $ac = 0;
        foreach ($keyvalues as $i => $keyvalue) {
            $ac += ($i & 1) ? $keyvalue : $keyvalue << 8;
        }
        $ac += ($ac >> 16) & 0xFFFF;

        return $ac & 0xFFFF;
    }

    /**
     * @param ExecutionContextInterface $context
     * @param $payload
     * @Assert\Callback(groups={"CREATE"})
     */
    public function validateAlgos(ExecutionContextInterface $context, $payload): void
    {
        if (null === $this->getAlgorithm()) {
            return;
        }

        if (\is_integer($this->getAlgorithm()) && !\array_key_exists($this->getAlgorithm(), self::VALID_ALGORITHMS)) {
            $context->buildViolation(sprintf('Algorithm "%s" is unknown', $this->getAlgorithm()))
                ->atPath('algorithm')
                ->addViolation();
        }

        if (\is_string($this->getAlgorithm()) && !\in_array($this->getAlgorithm(), self::VALID_ALGORITHMS, false)) {
            $context->buildViolation(sprintf('Algorithm "%s" is unknown', $this->getAlgorithm()))
                ->atPath('algorithm')
                ->addViolation();
        }
    }
}
