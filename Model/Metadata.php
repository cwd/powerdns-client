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

class Metadata
{
    // https://doc.powerdns.com/md/httpapi/api_spec/#zone-metadata
    protected const UPDATE_FORBIDDEN = [
        'NSEC3PARAM',
        'NSEC3NARROW',
        'PRESIGNED',
        'LUA-AXFR-SCRIPT',
    ];

    // https://doc.powerdns.com/authoritative/domainmetadata.html
    protected const VALID_KINDs = [
        'ALLOW-AXFR-FROM',
        'API-RECTIFY',
        'AXFR-SOURCE',
        'ALLOW-DNSUPDATE-FROM',
        'TSIG-ALLOW-DNSUPDATE',
        'FORWARD-DNSUPDATE',
        'SOA-EDIT-DNSUPDATE',
        'NOTIFY-DNSUPDATE',
        'ALSO-NOTIFY',
        'AXFR-MASTER-TSIG',
        'GSS-ALLOW-AXFR-PRINCIPAL',
        'GSS-ACCEPTOR-PRINCIPAL',
        'IXFR',
        'LUA-AXFR-SCRIPT',
        'NSEC3NARROW',
        'NSEC3PARAM',
        'PRESIGNED',
        'PUBLISH-CDNSKEY',
        'PUBLISH-CDS',
        'SOA-EDIT',
        'SOA-EDIT-API',
        'TSIG-ALLOW-AXFR',
        'TSIG-ALLOW-DNSUPDATE',
    ];

    /**
     * @var string
     * @Assert\NotBlank(groups={"CREATE", "UPDATE"})
     */
    protected $kind;

    /** @var string[] */
    protected $metadata = [];

    /**
     * @return string
     */
    public function getKind(): string
    {
        return $this->kind;
    }

    /**
     * @param string $kind
     *
     * @return Metadata
     */
    public function setKind(string $kind): Metadata
    {
        $this->kind = $kind;

        return $this;
    }

    /**
     * @return string[]
     */
    public function getMetadata(): array
    {
        return $this->metadata;
    }

    /**
     * @param string[] $metadata
     *
     * @return Metadata
     */
    public function setMetadata(array $metadata): Metadata
    {
        $this->metadata = $metadata;

        return $this;
    }

    /**
     * @param ExecutionContextInterface $context
     * @param $payload
     * @Assert\Callback(groups={"CREATE", "UPDATE"})
     */
    public function validateKinds(ExecutionContextInterface $context, $payload): void
    {
        if (\in_array($this->getKind(), self::VALID_KINDs, false)) {
            return;
        }

        if (0 === stripos($this->getKind(), 'X-')) {
            return;
        }

        $context->buildViolation(sprintf('Kind "%s" not in valid kinds or does not start with "X-"', $this->getKind()))
            ->atPath('kind')
            ->addViolation();
    }

    /**
     * @param ExecutionContextInterface $context
     * @param $payload
     * @Assert\Callback(groups={"UPDATE"})
     */
    public function validateForbidden(ExecutionContextInterface $context, $payload): void
    {
        if (\in_array($this->getKind(), self::UPDATE_FORBIDDEN, false)) {
            $context->buildViolation(sprintf('Kind "%s" cant be updated', $this->getKind()))
                ->atPath('kind')
                ->addViolation();
        }
    }
}
