<?php

namespace Nassau\CartoonBattle\Services\Game;

interface SynapseUserInterface
{
    /**
     * @return string
     */
    public function getUserId();

    /**
     * @return string
     */
    public function getPassword();

    /**
     * @return string
     */
    public function getName();
}
