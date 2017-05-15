<?php

namespace Nassau\CartoonBattle\AdminList;

use Doctrine\ORM\EntityManager;

use Nassau\CartoonBattle\Form\UnitAdminType;
use Kunstmaan\AdminListBundle\AdminList\FilterType\ORM;
use Kunstmaan\AdminListBundle\AdminList\Configurator\AbstractDoctrineORMAdminListConfigurator;
use Kunstmaan\AdminBundle\Helper\Security\Acl\AclHelper;

/**
 * The admin list configurator for Unit
 */
class UnitAdminListConfigurator extends AbstractDoctrineORMAdminListConfigurator
{
    /**
     * @param EntityManager $em The entity manager
     * @param AclHelper $aclHelper The acl helper
     */
    public function __construct(EntityManager $em, AclHelper $aclHelper = null)
    {
        parent::__construct($em, $aclHelper);
        $this->setAdminType(new UnitAdminType());
    }

    /**
     * Configure the visible columns
     */
    public function buildFields()
    {
        $this->addField('id', 'Id', true);
        $this->addField('image', 'Image', true, '@CartoonBattle/Admin/image.html.twig');
        $this->addField('name', 'Name', true);
        $this->addField('cardSet', 'Set', false);
        $this->addField('type', 'Type', false, '@CartoonBattle/Admin/unitType.html.twig');
        $this->addField('rarity', 'Rarity', false, '@CartoonBattle/Admin/rarity.html.twig');
        $this->addField('createdAt', 'Created at', true);
    }

    /**
     * Build filters for admin list
     */
    public function buildFilters()
    {
        $this->addFilter('id', new ORM\NumberFilterType('id'), 'Id');
        $this->addFilter('name', new ORM\StringFilterType('name'), 'Name');
        $this->addFilter('createdAt', new ORM\DateFilterType('createdAt'), 'Created at');
    }

    public function canDelete($item)
    {
        return false;
    }

    public function canAdd()
    {
        return false;
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
        return 'Unit';
    }


}
