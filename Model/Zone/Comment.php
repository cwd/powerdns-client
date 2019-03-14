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
    /**
     * @var string
     * @Assert\NotBlank(groups={"CREATE", "UPDATE"})
     * @Groups({"REPLACE", "CREATE"})
     */
    protected $content;

    /**
     * @var string
     * @Groups({"REPLACE", "CREATE"})
     * @Assert\NotBlank(groups={"CREATE", "UPDATE"})
     */
    protected $account;

    /** @var int */
    protected $modifiedAt;

    /**
     * @return string
     */
    public function getContent(): string
    {
        return $this->content;
    }

    /**
     * @param string $content
     *
     * @return Comment
     */
    public function setContent(string $content): Comment
    {
        $this->content = $content;

        return $this;
    }

    /**
     * @return string
     */
    public function getAccount(): ?string
    {
        return $this->account;
    }

    /**
     * @param string $account
     *
     * @return Comment
     */
    public function setAccount(?string $account): Comment
    {
        $this->account = $account;

        return $this;
    }

    /**
     * @return int
     */
    public function getModifiedAt(): ?int
    {
        return $this->modifiedAt;
    }

    /**
     * @param int $modifiedAt
     *
     * @return Comment
     */
    public function setModifiedAt(int $modifiedAt): Comment
    {
        $this->modifiedAt = $modifiedAt;

        return $this;
    }
}
