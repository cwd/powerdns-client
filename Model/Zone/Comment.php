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

class Comment
{
    #[Groups(['REPLACE', 'CREATE'])]
    #[Assert\NotBlank(groups: ["CREATE", "UPDATE"])]
    protected ?string $content = null;

    #[Groups(['REPLACE', 'CREATE'])]
    #[Assert\NotBlank(groups: ["CREATE", "UPDATE"])]
    protected ?string $account = null;

    protected ?int $modifiedAt = null;

    public function getContent(): string
    {
        return $this->content;
    }

    public function setContent(string $content): Comment
    {
        $this->content = $content;

        return $this;
    }

    public function getAccount(): ?string
    {
        return $this->account;
    }

    public function setAccount(?string $account): Comment
    {
        $this->account = $account;

        return $this;
    }

    public function getModifiedAt(): ?int
    {
        return $this->modifiedAt;
    }

    public function setModifiedAt(int $modifiedAt): Comment
    {
        $this->modifiedAt = $modifiedAt;

        return $this;
    }
}
