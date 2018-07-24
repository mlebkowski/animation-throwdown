<?php

namespace Nassau\CartoonBattle\Services\Farming\DTO;

final class FailedToStartBattle
{
    private $reason;

    public function __construct($reason)
    {
        $this->reason = (string)$reason;
    }

    /**
     * @return string
     */
    public function getReason()
    {
        return $this->reason;
    }


}