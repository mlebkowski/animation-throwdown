<?php

namespace Nassau\CartoonBattle\Entity\Guild;

use Gedmo\Timestampable\Traits\Timestampable;

class Guild
{
    use Timestampable;

    private $id;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $slug;

    /**
     * @var integer
     */
    private $factionId;

    /**
     * @var boolean
     */
    private $recruiting = false;

    /**
     * @var string
     */
    private $message;

    /**
     * @var string
     */
    private $url;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     *
     * @return $this
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * @param string $slug
     *
     * @return $this
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * @return int
     */
    public function getFactionId()
    {
        return $this->factionId;
    }

    /**
     * @param int $factionId
     *
     * @return $this
     */
    public function setFactionId($factionId)
    {
        $this->factionId = $factionId;

        return $this;
    }

    /**
     * @return bool
     */
    public function isRecruiting()
    {
        return $this->recruiting;
    }

    /**
     * @param bool $recruiting
     *
     * @return $this
     */
    public function setRecruiting($recruiting)
    {
        $this->recruiting = $recruiting;

        return $this;
    }

    /**
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @param string $message
     *
     * @return $this
     */
    public function setMessage($message)
    {
        $this->message = $message ?: null;

        return $this;
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @param string $url
     *
     * @return $this
     */
    public function setUrl($url)
    {
        $this->url = $url ?: null;

        return $this;
    }

    public function __toString()
    {
        return $this->name;
    }


}
