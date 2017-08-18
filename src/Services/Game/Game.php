<?php

namespace Nassau\CartoonBattle\Services\Game;

use GuzzleHttp\Client;
use Nassau\CartoonBattle\Services\Game\DTO\Guild;
use Nassau\CartoonBattle\Services\Game\DTO\Rumble;

class Game
{
    const ITEM_AD_CRATE = 30001;
    const ITEM_BASIC_PACK = 1;
    const ITEM_VIP_PASS = 202;

    /**
     * @var Client
     */
    private $client;

    /**
     * @var SynapseUserInterface
     */
    private $user;

    private $userData = [
        'money' => 0,
        'energy' => 0,
        'stamina' => 0,
    ];

    private $events = [];

    private $achievements = [];

    private $items = [];

    private $commonFields = [
        'total_spent_in_usd' => 0,
        'pvp_level' => 1,
    ];

    public function __construct(Client $client, SynapseUserInterface $user)
    {
        $this->client = $client;
        $this->user = $user;
    }

    public function isEnergySufficient($minutes = 120)
    {
        return ($this->userData['max_energy'] - $this->userData['energy']) * $this->userData['energy_recharge_time'] / 60 < $minutes;
    }

    public function getRemainingEnergy()
    {
        return $this->userData['energy'];
    }

    public function getRemainingStamina()
    {
        return $this->userData['stamina'];
    }

    public function getGuildMembers()
    {
        return $this('updateGuild')['faction']['members'];
    }

    public function getAchievements()
    {
        return $this->achievements;
    }

    public function isBattleInProgress()
    {
        $result = $this('init');

        return isset($result['active_battle_data']);
    }

    public function getQuest($id)
    {
        return isset($this->achievements[$id]) ? $this->achievements[$id] : null;
    }

    public function completeAllAchievements()
    {
        foreach ($this->achievements as $id => $achievement) {
            if ((bool)$achievement['status']) {
                $this->completeQuest($id);
                yield $achievement['description'];
            }
        }
    }

    public function completeQuest($id)
    {
        return $this('completeAchievement', ['achievement_id' => $id]);
    }

    public function getRandomHuntingTarget()
    {
        $result = $this('getHuntingTargets') + ['hunting_targets' => []];

        $targetId = array_rand($result['hunting_targets']);

        return $targetId ? $result['hunting_targets'][$targetId] : null;
    }

    public function init()
    {
        return $this('init');
    }

    public function getArenaLevel()
    {
        return $this->commonFields['pvp_level'];
    }

    public function isSpender()
    {
        return $this->commonFields['total_spent_in_usd'] > 100;
    }

    public function getMoney()
    {
        return $this->userData['money'];
    }

    public function hasMoney($percent = .25)
    {
        return $this->userData['money'] / $this->userData['money_cap'] > $percent;
    }

    public function searchGuildName($name)
    {
        return array_values(array_map(function ($guild) {
            return new Guild($guild);
        }, $this('searchGuildName', ['name' => $name])['guilds']));
    }

    public function buySinglePackItem()
    {
        $result = $this('buyStoreItem', [
            'item_id' => self::ITEM_BASIC_PACK,
            'expected_cost' => 1000,
            'cost_type' => 2
        ]);

        return isset($result['new_units']) ? $result['new_units'] : null;
    }

    public function upgradeUnit($unitIndex)
    {
        return $this('upgradeUnit', ['unit_index' => $unitIndex]);
    }

    public function recordAdBoost()
    {
        return $this('recordAdBoost');
    }

    public function isVIP()
    {
        return isset($this->items[self::ITEM_VIP_PASS]);
    }

    public function useAdCrate()
    {
        return $this('useAdLockedItem', ['item_id' => self::ITEM_AD_CRATE]);
    }

    public function getItemCount($itemId)
    {
        return isset($this->items[$itemId]) ? (int)$this->items[$itemId]['number'] : 0;
    }

    public function getEvents()
    {
        return $this->events;
    }

    public function startAdventureBattle($missionId)
    {
        $result = $this('startMission', ['mission_id' => $missionId]);

        return $result['battle_data']['battle_id'];
    }

    public function startPracticeBattle($targetUserId)
    {
        $result = $this('startPracticeBattle', ['target_user_id' => $targetUserId]);

        return $result['battle_data'];
    }

    public function skipBattle($battleId)
    {
        return $this('playCard', ['skip' => 'True', 'battle_id' => $battleId]);
    }

    public function startArenaBattle($targetUserId)
    {
        $result = $this('startHuntingBattle', ['target_user_id' => $targetUserId]);

        return $result['battle_data']['battle_id'];
    }

    public function startChallengeBattle($challengeId)
    {
        $result = $this('startChallenge', ['challenge_id' => $challengeId]);

        return $result['battle_data']['battle_id'];
    }

    public function getRumble()
    {
        return new Rumble($this('getGuildWarStatus'));
    }

    public function getRumbleStats(Rumble $rumble)
    {
        $result = $this('getRankings', ['ranking_index' => $rumble->getId(), 'ranking_id' => 'event_guild']);

        return $result['rankings']['data'];
    }

    public function getGuildInfo($factionId)
    {
        $result = $this('getGuildInfo', ['faction_id' => $factionId]);

        return $result['guild_info'];
    }

    public function __invoke($message, array $data = [], array $query = [])
    {
        $url = '?' . http_build_query($query + ['user_id' => $this->user->getUserId(), 'message' => $message]);
        $data += ['password' => $this->user->getPassword()];

        $body = $this->client->request('POST', $url, [
            'form_params' => $data,
        ])->getBody();

        $result = \GuzzleHttp\json_decode((string)$body, true);

        if (isset($result['user_data'])) {
            $this->userData = array_replace($this->userData, $result['user_data']);
        }

        if (isset($result['active_events'])) {
            $this->events = $result['active_events'];
        }

        if (isset($result['user_achievements'])) {
            $this->achievements = $result['user_achievements'];
        }

        if (isset($result['user_items'])) {
            $this->items = $result['user_items'];
        }

        if (isset($result['common_fields'])) {
            $this->commonFields = $result['common_fields'];
        }

        return $result;
    }


}
