<?php

namespace Nassau\CartoonBattle\Entity;

use Kunstmaan\MediaBundle\Entity\Media;

class UnitType
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $name;

    /**
     * @var Media
     */
    private $logo;

    /**
     * @param int $id
     * @param string $name
     */
    public function __construct($id, $name)
    {
        $this->id = $id;
        $this->name = $name;
    }


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
     * @return Media
     */
    public function getLogo()
    {
        return $this->logo;
    }

    /**
     * @param Media $logo
     *
     * @return $this
     */
    public function setLogo(Media $logo)
    {
        $this->logo = $logo;

        return $this;
    }

    public function __toString()
    {
        return $this->name;
    }

    public function getPrefix()
    {
        return ['FG', 'AD', 'BB', 'KH', 'FT', 'generic'][$this->id - 1];
    }


}
