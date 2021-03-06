<?php

namespace Nassau\CartoonBattle\Services\Game\DTO;

class Rumble
{
    private $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function getId()
    {
        return $this->data['guild_war_event_data']['id'];
    }

    public function isActive()
    {
        return isset($this->data['guild_war_active']) && false !== $this->data['guild_war_active'];
    }

    public function isFightingOver()
    {
        return $this->data['guild_war_event_data']['tracking_end_time'] + (60*60) < time();
    }

    public function getTotalMatches()
    {
        return (int)$this->data['guild_war_event_data']['total_matches'];
    }

    public function getEnergy()
    {
        return $this->data['guild_war_event_data']['energy']['current_value'];
    }

    public function getHighestStartedMatchNumber()
    {
        return sizeof(array_filter($this->data['guild_war_matches'], function (array $match) {
            return $match['us_kills'] > 0;
        }));
    }

    public function getStart()
    {
        return \DateTime::createFromFormat('U', $this->data['guild_war_event_data']['start_time']);
    }

    public function getEnd()
    {
        return \DateTime::createFromFormat('U', $this->data['guild_war_event_data']['end_time']);
    }

    public function getMatchStartTime()
    {
        return \DateTime::createFromFormat("U", $this->data['guild_war_event_data']['match_start_time']);
    }

    public function getMatches()
    {
        return array_reverse($this->data['guild_war_matches']);
    }

    public function getCurrentMatch()
    {
        return new RumbleCurrentMatch($this->data['guild_war_current_match'] ?: []);
    }

}
