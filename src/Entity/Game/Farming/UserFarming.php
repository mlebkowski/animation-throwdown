<?php

namespace Nassau\CartoonBattle\Entity\Game\Farming;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Nassau\CartoonBattle\Entity\Game\Hero;
use Nassau\CartoonBattle\Entity\Game\User;
use Nassau\CartoonBattle\Services\Farming\DTO\BattleTarget;
use Nassau\CartoonBattle\Services\Game\Game;

class UserFarming
{
    const SETTING_ADVENTURE = 'adventure';
    const SETTING_ADVENTURE_REFILL = 'adventure-refill';
    const SETTING_ARENA = 'arena';
    const SETTING_ARENA_REFILL = 'arena-refill';
    const SETTING_CHALLENGES = 'challenges';
    const SETTING_CARDS = 'cards';
    const SETTING_GOLD = 'gold';
    const SETTING_RUMBLE = 'rumble';

    /**
     * @var string
     */
    private $id;

    /**
     * @var User
     */
    private $user;

    /**
     * @var boolean
     */
    private $enabled;

    /**
     * @var array
     */
    private $settings = [];

    /**
     * @var array
     */
    private $adventureMissions = [];

    /**
     * @var UserFarmingReferralCode
     */
    private $referralCode;

    /**
     * @var string|null
     */
    private $subscription;

    /**
     * @var string
     */
    private $comment = "";

    /**
     * @var \DateTime
     */
    private $expiresAt;

    /**
     * @var UserFarmingLog[]|Collection
     */
    private $logs;

    /**
     * @var UserFarmingResult[]|Collection
     */
    private $results;

    /**
     * @var Hero
     */
    private $adventureHero;

    private $runtimeSettings = [];

    public function __construct()
    {
        $this->logs = new ArrayCollection();
        $this->results = new ArrayCollection();
        $this->expiresAt = new \DateTime("+3 days");
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
     * @param User $user
     *
     * @return $this
     */
    public function setUser(User $user)
    {
        $this->user = $user;

        return $this;
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
        $this->enabled = $enabled;

        return $this;
    }

    /**
     * @return array
     */
    public function getSettings()
    {
        return $this->settings;
    }

    public function setSettings(array $settings)
    {
        $this->settings = $settings ?: [];

        return $this;
    }

    /**
     * @return array
     */
    public function getAdventureMissions()
    {
        return $this->adventureMissions ? array_filter($this->adventureMissions) : [];
    }

    public function setAdventureMissions(array $adventureMissions)
    {
        $this->adventureMissions = $adventureMissions ?: [];

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getExpiresAt()
    {
        return $this->expiresAt;
    }

    /**
     * @param \DateTime $expiresAt
     *
     * @return $this
     */
    public function setExpiresAt(\DateTime $expiresAt)
    {
        $this->expiresAt = $expiresAt;

        return $this;
    }

    /**
     * @return UserFarmingReferralCode|null
     */
    public function getReferralCode()
    {
        return $this->referralCode;
    }

    /**
     * @param UserFarmingReferralCode $referralCode
     */
    public function setReferralCode(UserFarmingReferralCode $referralCode = null)
    {
        $this->referralCode = $referralCode;
    }

    /**
     * @return Collection|UserFarmingLog[]
     */
    public function getLogs()
    {
        return $this->logs;
    }

    /**
     * @return UserFarmingLog
     */
    public function createNewLog()
    {
        $log = new UserFarmingLog();

        $this->addLog($log);

        return $log;
    }

    public function addLog(UserFarmingLog $log)
    {
        $this->logs->add($log->setUserFarming($this));

        return $this;
    }

    /**
     * @return Collection|UserFarmingResult[]
     */
    public function getResults()
    {
        return $this->results;
    }

    public function addResult(BattleTarget $nextTarget, $winner, array $result)
    {
        $this->results->add(new UserFarmingResult($this, $nextTarget->getType(), $nextTarget->getTarget(), $winner, $result));
    }

    /**
     * @return null|string
     */
    public function getSubscription()
    {
        return $this->subscription;
    }

    /**
     * @param null|string $subscription
     *
     * @return $this
     */
    public function setSubscription($subscription)
    {
        $this->subscription = $subscription;

        return $this;
    }

    public function getComment()
    {
        return $this->comment;
    }

    public function setComment($comment)
    {
        $this->comment = (string)$comment;

        return $this;
    }

    /**
     * @return Hero|null
     */
    public function getAdventureHero()
    {
        return $this->adventureHero;
    }

    public function updateFreeTrial(Game $game)
    {
        if (null === $this->referralCode || false === $this->referralCode->hasFreeTier()) {
            return false;
        }

        if ($game->isSpender() || $game->getArenaLevel() < 10) {
            return false;
        }

        $this->expiresAt = max($this->expiresAt, new \DateTime('14 days'));

        return true;
    }

    public function isVIP()
    {
        return null !== $this->getSubscription() || isset($this->runtimeSettings['vip']);
    }

    public function setRuntimeSettings(array $settings)
    {
        $this->runtimeSettings = $settings;
    }

    public function has($setting)
    {
        return in_array($setting, $this->settings) || in_array($setting, $this->runtimeSettings);
    }

    public function __toString()
    {
        return sprintf('Farming[%s]' , $this->getUser()->getName());
    }

    public function getMinLevel()
    {
        if (null === $this->getReferralCode()) {
            return 15;
        }

        return $this->getReferralCode()->getMinLevel() ?: 15;
    }

}
