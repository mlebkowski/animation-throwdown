<?php


namespace Nassau\CartoonBattle\Entity\Paypal;


use Gedmo\Timestampable\Traits\Timestampable;

class InstantNotification
{
    use Timestampable;

    private $id;

    private $payload;

    public function __construct($payload)
    {
        $this->payload = $payload;
    }


    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getPayload()
    {
        return $this->payload;
    }
}