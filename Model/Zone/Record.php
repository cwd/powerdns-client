<?php


namespace Cwd\PowerDNSClient\Model\Zone;


class Record
{
    /** @var string */
    private $content;
    /** @var bool  */
    private $disabled = false;
    /** @var  */
    private $setPtr = false;

    /**
     * @return string
     */
    public function getContent(): string
    {
        return $this->content;
    }

    /**
     * @param string $content
     * @return Record
     */
    public function setContent(string $content): Record
    {
        $this->content = $content;
        return $this;
    }

    /**
     * @return bool
     */
    public function isDisabled(): bool
    {
        return $this->disabled;
    }

    /**
     * @param bool $disabled
     * @return Record
     */
    public function setDisabled(bool $disabled): Record
    {
        $this->disabled = $disabled;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getSetPtr()
    {
        return $this->setPtr;
    }

    /**
     * @param mixed $setPtr
     * @return Record
     */
    public function setSetPtr($setPtr)
    {
        $this->setPtr = $setPtr;
        return $this;
    }
}