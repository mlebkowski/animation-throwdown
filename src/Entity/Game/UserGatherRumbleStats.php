<?php

namespace Nassau\CartoonBattle\Entity\Game;

class UserGatherRumbleStats
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
    private $enabled;

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
    public function isEnabled()
    {
        return $this->enabled;
    }

    /**
     * @param bool $enabled
     *
     * @return $this
     */
    public function setEnabled($enabled)
    {
        $this->enabled = (bool)$enabled;

        return $this;
    }




}
