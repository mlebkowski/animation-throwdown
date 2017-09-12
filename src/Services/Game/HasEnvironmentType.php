<?php

namespace Nassau\CartoonBattle\Services\Game;

interface HasEnvironmentType
{
    const PROD = 'prod';
    const DEV = 'dev';

    /**
     * @return string
     */
    public function getEnvironmentType();
}