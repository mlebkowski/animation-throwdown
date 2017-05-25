<?php

namespace Nassau\CartoonBattle\AdminList;

use Doctrine\ORM\EntityManager;

use Kunstmaan\AdminBundle\Helper\Security\Acl\Permission\PermissionDefinition;
use Kunstmaan\AdminBundle\Helper\Security\Acl\Permission\PermissionMap;
use Nassau\CartoonBattle\Entity\Guild\Guild;
use Nassau\CartoonBattle\Form\GuildAdminType;
use Kunstmaan\AdminListBundle\AdminList\FilterType\ORM;
use Kunstmaan\AdminListBundle\AdminList\Configurator\AbstractDoctrineORMAdminListConfigurator;
use Kunstmaan\AdminBundle\Helper\Security\Acl\AclHelper;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

/**
 * The admin list configurator for Guild
 */
class GuildAdminListConfigurator extends AbstractDoctrineORMAdminListConfigurator
{
    private $authorizationChecker;

    /**
     * @param EntityManager $em
     * @param AclHelper $aclHelper
     * @param AuthorizationCheckerInterface $authorizationChecker
     */
    public function __construct(EntityManager $em, AclHelper $aclHelper, AuthorizationCheckerInterface $authorizationChecker)
    {
        parent::__construct($em, $aclHelper);
        $this->setAdminType(new GuildAdminType($authorizationChecker));

        $this->authorizationChecker = $authorizationChecker;

        // don’t restrict for role bearers:
        if (false === $this->canAdd()) {
            $this->setPermissionDefinition(new PermissionDefinition([PermissionMap::PERMISSION_VIEW], Guild::class));
        }

    }

    /**
     * Configure the visible columns
     */
    public function buildFields()
    {
        $this->addField('name', 'Name', true);
        $this->addField('recruiting', 'Recruiting', true);
        $this->addField('url', 'Url', true);
        $this->addField('createdAt', 'Created at', true);
    }

    /**
     * Build filters for admin list
     */
    public function buildFilters()
    {
        $this->addFilter('name', new ORM\StringFilterType('name'), 'Name');
        $this->addFilter('factionId', new ORM\NumberFilterType('factionId'), 'Faction id');
        $this->addFilter('recruiting', new ORM\BooleanFilterType('recruiting'), 'Recruiting');
    }

    public function canAdd()
    {
        return $this->authorizationChecker->isGranted(PermissionMap::PERMISSION_EDIT, new Guild);
    }

    public function canEdit($item)
    {
        return $this->authorizationChecker->isGranted(PermissionMap::PERMISSION_EDIT, $item);
    }

    public function canDelete($item)
    {
        return $this->authorizationChecker->isGranted(PermissionMap::PERMISSION_DELETE, $item);
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
        return 'Guild\Guild';
    }


}
