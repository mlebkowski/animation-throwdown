<?php

namespace Nassau\CartoonBattle\AdminList;

use Doctrine\ORM\EntityManager;

use Nassau\CartoonBattle\Form\RumbleAdminType;
use Kunstmaan\AdminListBundle\AdminList\Configurator\AbstractDoctrineORMAdminListConfigurator;
use Kunstmaan\AdminBundle\Helper\Security\Acl\AclHelper;

/**
 * The admin list configurator for Rumble
 */
class RumbleAdminListConfigurator extends AbstractDoctrineORMAdminListConfigurator {
    /**
     * @param EntityManager $em        The entity manager
     * @param AclHelper     $aclHelper The acl helper
     */
    public function __construct(EntityManager $em, AclHelper $aclHelper = null)
    {
        parent::__construct($em, $aclHelper);
        $this->setAdminType(new RumbleAdminType());
    }

    /**
     * Configure the visible columns
     */
    public function buildFields()
    {
        $this->addField('start', 'Start', true);
        $this->addField('end', 'End', true);
    }

    /**
     * Get bundle name
     *
     * @return string
     */
    public function getBundleName()
    {
        return 'CartoonBattleBundle';
    }

    /**
     * Get entity name
     *
     * @return string
     */
    public function getEntityName()
    {
        return 'Rumble\Rumble';
    }


}
