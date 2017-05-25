<?php

namespace Nassau\CartoonBattle\Entity\Guild;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Gedmo\Timestampable\Traits\Timestampable;
use Kunstmaan\AdminBundle\Entity\User;
use Nassau\CartoonBattle\Entity\AclObjectTrait;
use Nassau\CartoonBattle\Entity\Rumble\RumbleStanding;
use Nassau\CartoonBattle\Services\Acl\HasModeratorsInterface;

class Guild implements HasModeratorsInterface
{
    use Timestampable;
    use AclObjectTrait;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $slug;

    /**
     * @var integer
     */
    private $factionId;

    /**
     * @var boolean
     */
    private $recruiting = false;

    /**
     * @var string
     */
    private $message;

    /**
     * @var string
     */
    private $url;

    /**
     * @var User[]|Collection
     */
    private $moderators;

    /**
     * @var RumbleStanding[]|Collection
     */
    private $standings;

    public function __construct()
    {
        $this->standings = new ArrayCollection();
        $this->moderators = new ArrayCollection();
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

    /**
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * @param string $slug
     *
     * @return $this
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * @return int
     */
    public function getFactionId()
    {
        return $this->factionId;
    }

    /**
     * @param int $factionId
     *
     * @return $this
     */
    public function setFactionId($factionId)
    {
        $this->factionId = $factionId;

        return $this;
    }

    /**
     * @return bool
     */
    public function isRecruiting()
    {
        return $this->recruiting;
    }

    /**
     * @param bool $recruiting
     *
     * @return $this
     */
    public function setRecruiting($recruiting)
    {
        $this->recruiting = $recruiting;

        return $this;
    }

    /**
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @param string $message
     *
     * @return $this
     */
    public function setMessage($message)
    {
        $this->message = $message ?: null;

        return $this;
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @param string $url
     *
     * @return $this
     */
    public function setUrl($url)
    {
        $this->url = $url ?: null;

        return $this;
    }

    /**
     * @return Collection|RumbleStanding[]
     */
    public function getStandings()
    {
        return $this->standings;
    }

    public function addStanding(RumbleStanding $standing)
    {
        $this->standings->add($standing->setGuild($this));

        return $this;
    }

    public function removeStanding(RumbleStanding $standing)
    {
        $this->standings->removeElement($standing);

        return $this;
    }

    /**
     * @return Collection|User[]
     */
    public function getModerators()
    {
        return $this->moderators;
    }

    public function addModerator(User $user)
    {
        $this->moderators->add($user);

        return $this;
    }

    public function removeModerator(User $user)
    {
        $this->moderators->removeElement($user);

        return $this;
    }

    public function __toString()
    {
        return $this->name;
    }

}
