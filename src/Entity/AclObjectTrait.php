<?php

namespace Nassau\CartoonBattle\Entity;

use Doctrine\Common\Proxy\Proxy;
use Symfony\Component\Security\Acl\Model\ObjectIdentityInterface;

trait AclObjectTrait
{

    protected $id;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * We specifically require this method so we can check for object equality
     * explicitly, and do not have to rely on referencial equality instead.
     *
     * Though in most cases, both checks should result in the same outcome.
     *
     * Referential Equality: $object1 === $object2
     * Example for Object Equality: $object1->getId() === $object2->getId()
     *
     * @param ObjectIdentityInterface $identity
     *
     * @return bool
     */
    public function equals(ObjectIdentityInterface $identity)
    {
        return $identity->getIdentifier() === $this->getIdentifier();
    }

    /**
     * Obtains a unique identifier for this object. The identifier must not be
     * re-used for other objects with the same type.
     *
     * @return string cannot return null
     */
    public function getIdentifier()
    {
        return $this->getId();
    }

    /**
     * Returns a type for the domain object. Typically, this is the PHP class name.
     *
     * @return string cannot return null
     */
    public function getType()
    {
        return $this instanceof Proxy ? get_parent_class($this) : get_class($this);
    }
}
