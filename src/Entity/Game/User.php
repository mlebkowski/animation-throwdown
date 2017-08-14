<?php

namespace Nassau\CartoonBattle\Entity\Game;

use Gedmo\Timestampable\Traits\Timestampable;
use Nassau\CartoonBattle\Entity\Game\Farming\UserFarming;
use Nassau\CartoonBattle\Services\Game\SynapseUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class User implements SynapseUserInterface, UserInterface
{
    const ROLE_USER = self::class;

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
     * @var UserFarming
     */
    private $farming;

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

    /**
     * @return UserFarming|null
     */
    public function getFarming()
    {
        return $this->farming;
    }


    public function getRoles()
    {
        return [self::ROLE_USER];
    }

    /**
     * Returns the salt that was originally used to encode the password.
     *
     * This can return null if the password was not encoded using a salt.
     *
     * @return string|null The salt
     */
    public function getSalt()
    {
        return null;
    }

    /**
     * Returns the username used to authenticate the user.
     *
     * @return string The username
     */
    public function getUsername()
    {
        return $this->userId;
    }

    public function eraseCredentials()
    {
        // noop
    }

    public function __toString()
    {
        return sprintf('%s [%s]', $this->name, $this->userId);
    }
}
