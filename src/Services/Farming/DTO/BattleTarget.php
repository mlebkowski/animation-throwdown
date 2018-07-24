<?php

namespace Nassau\CartoonBattle\Services\Farming\DTO;

final class BattleTarget
{
    private $type;

    private $label;

    private $target;

    public function __construct($type, $label, $target)
    {
        $this->type = $type;
        $this->label = $label;
        $this->target = $target;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @return string
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * @return string
     */
    public function getTarget()
    {
        return $this->target;
    }

}
