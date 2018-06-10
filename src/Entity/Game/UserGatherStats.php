<?php

namespace Nassau\CartoonBattle\Entity\Game;

class UserGatherStats
{
    /**
     * @var string
     */
    private $id;

    /**
     * @var User
     */
    private $user;

    /**
     * @var bool
     */
    private $rumble;

    /**
     * @var bool
     */
    private $siege;

    /**
     * @param User $user
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @return bool
     */
    public function isRumble()
    {
        return $this->rumble;
    }

    /**
     * @param bool $rumble
     *
     * @return $this
     */
    public function setRumble($rumble)
    {
        $this->rumble = (bool)$rumble;

        return $this;
    }

    /**
     * @return bool
     */
    public function isSiege()
    {
        return $this->siege;
    }

    /**
     * @param bool $siege
     *
     * @return $this
     */
    public function setSiege($siege)
    {
        $this->siege = (bool)$siege;

        return $this;
    }

}
