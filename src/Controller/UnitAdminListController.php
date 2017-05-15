<?php

namespace Nassau\CartoonBattle\Controller;

use Kunstmaan\AdminListBundle\AdminList\Configurator\AbstractAdminListConfigurator;
use Nassau\CartoonBattle\AdminList\UnitAdminListConfigurator;
use Kunstmaan\AdminListBundle\Controller\AdminListController;
use Kunstmaan\AdminListBundle\AdminList\Configurator\AdminListConfiguratorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * The admin list controller for Unit
 */
class UnitAdminListController extends AdminListController
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
            /** @noinspection PhpParamsInspection */
            $this->configurator = new UnitAdminListConfigurator($this->getEntityManager());
        }

        return $this->configurator;
    }

    /**
     * The index action
     *
     * @Route("/", name="cartoonbattlebundle_admin_unit")
     */
    public function indexAction(Request $request)
    {
        return parent::doIndexAction($this->getAdminListConfigurator(), $request);
    }

    /**
     * The edit action
     *
     * @param Request $request
     * @param int $id
     * @return array|Response
     * @Route("/{id}", requirements={"id" = "\d+"}, name="cartoonbattlebundle_admin_unit_edit")
     * @Method({"GET", "POST"})
     *
     */
    public function editAction(Request $request, $id)
    {
        return parent::doEditAction($this->getAdminListConfigurator(), $id, $request);
    }

    /**
     * The edit action
     *
     * @param int $id
     *
     * @Route("/{id}", requirements={"id" = "\d+"}, name="cartoonbattlebundle_admin_unit_view")
     * @Method({"GET"})
     *
     * @return array
     */
    public function viewAction(Request $request, $id)
    {
        return parent::doViewAction($this->getAdminListConfigurator(), $id, $request);
    }

}
