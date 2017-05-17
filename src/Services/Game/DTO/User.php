<?php

namespace Nassau\CartoonBattle\Services\Game\DTO;

use Nassau\CartoonBattle\Services\Game\SynapseUserInterface;

class User implements SynapseUserInterface
{
    private $userId;

    private $password;

    /**
     * @param $userId
     * @param $password
     */
    public function __construct($userId, $password)
    {
        $this->userId = $userId;
        $this->password = $password;
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


}
