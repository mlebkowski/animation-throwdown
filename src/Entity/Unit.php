<?php

namespace Nassau\CartoonBattle\Entity;

use Kunstmaan\MediaBundle\Entity\Media;

class Unit
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var Media
     */
    private $image;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $slug;

    /**
     * @var string
     */
    private $picture;

    /**
     * @var bool
     */
    private $commander;

    /**
     * @var Rarity
     */
    private $rarity;

    /**
     * @var CardSet
     */
    private $cardSet;

    /**
     * @var UnitType
     */
    private $type;

    /**
     * @var \DateTime
     */
    private $createdAt;

    /**
     * @var \DateTime
     */
    private $updatedAt;

    /**
     * @var CardImageStorage
     */
    private $imageStorage;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * @return string
     */
    public function getPicture()
    {
        return $this->picture;
    }

    /**
     * @return bool
     */
    public function isCommander()
    {
        return $this->commander;
    }

    /**
     * @return Rarity
     */
    public function getRarity()
    {
        return $this->rarity;
    }

    /**
     * @return CardSet
     */
    public function getCardSet()
    {
        return $this->cardSet;
    }

    /**
     * @return UnitType
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @return Media
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * @param Media $image
     *
     * @return $this
     */
    public function setImage(Media $image)
    {
        $this->image = $image;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * @param \DateTime $updatedAt
     *
     * @return $this
     */
    public function setUpdatedAt(\DateTime $updatedAt)
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * @return CardImageStorage
     */
    public function getImageStorage()
    {
        return $this->imageStorage ?: $this->imageStorage = new CardImageStorage($this);
    }

    public function getImageUrl()
    {
        return sprintf(
            'deck/cards/%s_%s.png',
            $this->getType()->getPrefix(),
            str_replace('/^(fg|koth|ad|bb|ft|kh|fr|generic)_/i', '', $this->getPicture())
        );
    }


}
