<?php

namespace Nassau\CartoonBattle\Entity\Game\Farming;

use Gedmo\Timestampable\Traits\Timestampable;

class UserFarmingLog
{
    use Timestampable;

    /**
     * @var string
     */
    private $id;

    /**
     * @var UserFarming
     */
    private $userFarming;

    /**
     * @var string
     */
    private $content;

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return UserFarming
     */
    public function getUserFarming()
    {
        return $this->userFarming;
    }

    /**
     * @param UserFarming $userFarming
     *
     * @return $this
     */
    public function setUserFarming(UserFarming $userFarming)
    {
        $this->userFarming = $userFarming;

        return $this;
    }

    /**
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @param string $content
     *
     * @return $this
     */
    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    public function appendContent($string)
    {
        $this->content .= $string;

        return $this;
    }



}
