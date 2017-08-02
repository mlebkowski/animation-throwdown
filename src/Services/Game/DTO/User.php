<?php

namespace Nassau\CartoonBattle\Services\Game\DTO;

use Nassau\CartoonBattle\Services\Game\SynapseUserInterface;

class User implements SynapseUserInterface
{
    private $userId;

    private $password;

    private $name;

    public function __construct($userId, $password, $name = 'Player')
    {
        $this->userId = $userId;
        $this->password = $password;
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

}
