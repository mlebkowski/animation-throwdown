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

    public function getFinishedMatchesCount()
    {
        return sizeof(array_filter($this->data['guild_war_matches'], function (array $match) {
            return $match['end_time'] + 60*30 < time();
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

}