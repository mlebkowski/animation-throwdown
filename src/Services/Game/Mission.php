<?php

namespace Nassau\CartoonBattle\Services\AnimationThrowdown;

class Mission
{
    private $chapter;

    private $number;

    public function __construct($chapter, $number)
    {
        if ($chapter < 1 || $chapter > 30) {
            throw new \InvalidArgumentException(sprintf(
                '<error>Mission chapter needs to be in range from 1 to 30, %s given',
                $chapter
            ));
        }

        if ($number < 1 || $number > 3) {
            throw new \InvalidArgumentException(sprintf(
                '<error>Mission number needs to be in range from 1 to 3, %s given', $number
            ));
        }


        $this->number = (int)$number;
        $this->chapter = (int)$chapter;
    }

    public function getEnergyRequired()
    {
        return 3 + ceil($this->chapter / 5);
    }

    /**
     * @param $code
     *
     * @return Mission
     */
    public static function fromCode($code)
    {
        list ($chapter, $number) = array_map('intval', array_pad(explode('-', $code), 2, 0));

        return new self($chapter, $number);
    }

    /**
     * @return int
     */
    public function getChapter()
    {
        return $this->chapter;
    }

    /**
     * @return int
     */
    public function getNumber()
    {
        return $this->number;
    }

    /**
     * @return int
     */
    public function getMissionId()
    {
        return 100 + ($this->chapter - 1) * 3 + $this->number;
    }

    public function getCode()
    {
        return sprintf('%d-%d', $this->chapter, $this->number);
    }

}
