<?php

namespace Nassau\CartoonBattle\Controller;

use Kunstmaan\AdminBundle\Helper\Security\Acl\Permission\PermissionMap;
use Kunstmaan\AdminListBundle\AdminList\Configurator\AbstractAdminListConfigurator;
use Nassau\CartoonBattle\AdminList\GuildAdminListConfigurator;
use Kunstmaan\AdminListBundle\Controller\AdminListController;
use Kunstmaan\AdminListBundle\AdminList\Configurator\AdminListConfiguratorInterface;
use Nassau\CartoonBattle\Entity\Guild\Guild;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * The admin list controller for Guild
 */
class GuildAdminListController extends AdminListController
{
    /**
     * @var AdminListConfiguratorInterface
     */
    private $configurator;

    /**
     * @return AdminListConfiguratorInterface|AbstractAdminListConfigurator
     */
    public function getAdminListConfigurator()
    {
        if (!isset($this->configurator)) {
            $this->configurator = new GuildAdminListConfigurator(
                $this->get('doctrine.orm.entity_manager'),
                $this->get('kunstmaan_admin.acl.helper'),
                $this->get('security.authorization_checker')
            );
        }

        return $this->configurator;
    }

    /**
     * The index action
     *
     * @Route("/", name="cartoonbattlebundle_admin_guild_guild")
     * @param Request $request
     * @return array
     */
    public function indexAction(Request $request)
    {
        return parent::doIndexAction($this->getAdminListConfigurator(), $request);
    }

    /**
     * The add action
     *
     * @Route("/add", name="cartoonbattlebundle_admin_guild_guild_add")
     * @Method({"GET", "POST"})
     * @param Request $request
     * @return array
     */
    public function addAction(Request $request)
    {
        $this->denyAccessUnlessGranted(PermissionMap::PERMISSION_EDIT, Guild::class);

        return parent::doAddAction($this->getAdminListConfigurator(), null, $request);
    }

    /**
     * The edit action
     *
     * @param Request $request
     * @param Guild $guild
     * @return array|Response
     * @Route("/{id}", requirements={"id" = "\d+"}, name="cartoonbattlebundle_admin_guild_guild_edit")
     * @Method({"GET", "POST"})
     *
     */
    public function editAction(Request $request, Guild $guild)
    {
        $this->denyAccessUnlessGranted(PermissionMap::PERMISSION_EDIT, $guild);

        return parent::doEditAction($this->getAdminListConfigurator(), $guild->getId(), $request);
    }

    /**
     * The delete action
     *
     * @param Request $request
     * @param Guild $guild
     * @return array|Response
     * @Route("/{id}/delete", requirements={"id" = "\d+"}, name="cartoonbattlebundle_admin_guild_guild_delete")
     * @Method({"GET", "POST"})
     *
     */
    public function deleteAction(Request $request, Guild $guild)
    {
        $this->denyAccessUnlessGranted(PermissionMap::PERMISSION_DELETE, $guild);

        return parent::doDeleteAction($this->getAdminListConfigurator(), $guild->getId(), $request);
    }

}
