<?php

namespace Nassau\CartoonBattle\Entity;

class CardImageStorage
{
    private $id;

    /**
     * @var Unit
     */
    private $unit;

    /**
     * @var string
     */
    private $sourceUrl;

    /**
     * @var \DateTime
     */
    private $updatedAt;

    /**
     * @param Unit $unit
     */
    public function __construct(Unit $unit)
    {
        $this->unit = $unit;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getSourceUrl()
    {
        return $this->sourceUrl;
    }

    /**
     * @param string $sourceUrl
     *
     * @return $this
     */
    public function setSourceUrl($sourceUrl)
    {
        $this->sourceUrl = $sourceUrl;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

}
