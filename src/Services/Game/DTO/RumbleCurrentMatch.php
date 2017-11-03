<?php


namespace Nassau\CartoonBattle\Services\Game\DTO;


class RumbleCurrentMatch
{
    private $data;

    public function __construct(array $data)
    {
        $this->data = array_replace(['them_name' => "Unknown", 'end_time' => time()], $data);
    }

    public function getEnemyName()
    {
        return $this->data['them_name'];
    }

    public function getEndTime()
    {
        return new \DateTime('@' . $this->data['end_time']);
    }

}
