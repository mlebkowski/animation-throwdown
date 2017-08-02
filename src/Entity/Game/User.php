<?php

namespace Nassau\CartoonBattle\Entity\Game;

use Gedmo\Timestampable\Traits\Timestampable;
use Nassau\CartoonBattle\Services\Game\SynapseUserInterface;

class User implements SynapseUserInterface
{
    use Timestampable;

    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $name;

    /**
     * @var int
     */
    private $userId;

    /**
     * @var string
     */
    private $password;

    /**
     * @param string $name
     * @param int $userId
     * @param string $password
     */
    public function __construct($name, $userId, $password)
    {
        $this->name = $name;
        $this->userId = $userId;
        $this->password = $password;
    }

    public static function fromUser(SynapseUserInterface $user)
    {
        return new User($user->getName(), $user->getUserId(), $user->getPassword());
    }

    /**
     * @return string
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
     * @param string $name
     *
     * @return $this
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * @param string $userId
     *
     * @return $this
     */
    public function setUserId($userId)
    {
        $this->userId = $userId;

        return $this;
    }

    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param string $password
     *
     * @return $this
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }
}
