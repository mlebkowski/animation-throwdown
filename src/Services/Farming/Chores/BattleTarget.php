<?php

namespace Nassau\CartoonBattle\Services\Farming\Chores;

class BattleTarget
{
    private $type;

    private $label;

    private $target;

    private $comment;

    public function __construct($type, $label, $target, $comment = '')
    {
        $this->type = $type;
        $this->label = $label;
        $this->target = $target;
        $this->comment = $comment;
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

    /**
     * @return string
     */
    public function getComment()
    {
        return $this->comment;
    }

}
