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

namespace Cwd\PowerDNSClient\Model\Zone;

use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

class Record
{
    #[Groups(['REPLACE', 'CREATE'])]
    #[Assert\NotBlank(groups: ['CREATE', 'UPDATE'])]
    protected ?string $content = null;

    #[Groups(['REPLACE', 'CREATE', 'DELETE'])]
    protected bool $disabled = false;

    #[Groups(['REPLACE', 'CREATE', 'DELETE'])]
    protected bool $setPtr = false;

    public function getContent(): string
    {
        return $this->content;
    }

    public function setContent(string $content): Record
    {
        $this->content = $content;

        return $this;
    }

    public function isDisabled(): bool
    {
        return $this->disabled;
    }

    public function setDisabled(bool $disabled): Record
    {
        $this->disabled = $disabled;

        return $this;
    }

    public function getSetPtr(): bool
    {
        return $this->setPtr;
    }

    public function setSetPtr(bool $setPtr): Record
    {
        $this->setPtr = $setPtr;

        return $this;
    }
}
