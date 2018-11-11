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

class Metadata
{
    /** @var string */
    private $kind;
    /** @var string[] */
    private $metadata = [];

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
}
