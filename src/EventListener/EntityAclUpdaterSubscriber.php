<?php

namespace Nassau\CartoonBattle\EventListener;

use Kunstmaan\AdminListBundle\Event\AdminListEvent;
use Kunstmaan\AdminListBundle\Event\AdminListEvents;
use Nassau\CartoonBattle\Services\Acl\AclModeratorsUpdater;
use Nassau\CartoonBattle\Services\Acl\HasModeratorsInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class EntityAclUpdaterSubscriber implements EventSubscriberInterface
{

    /**
     * @var AclModeratorsUpdater
     */
    private $aclUpdater;

    public function __construct(AclModeratorsUpdater $aclUpdater)
    {
        $this->aclUpdater = $aclUpdater;
    }


    public static function getSubscribedEvents()
    {
        return [
            AdminListEvents::POST_ADD => 'updateAcl',
            AdminListEvents::POST_EDIT => 'updateAcl',
        ];
    }

    public function updateAcl(AdminListEvent $event)
    {
        $entity = $event->getEntity();

        if ($entity instanceof HasModeratorsInterface) {
            $this->aclUpdater->updateAcl($entity);
        }
    }
}
