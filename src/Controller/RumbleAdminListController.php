<?php

namespace Nassau\CartoonBattle\Controller;

use Kunstmaan\AdminListBundle\AdminList\Configurator\AbstractAdminListConfigurator;
use Nassau\CartoonBattle\AdminList\RumbleAdminListConfigurator;
use Kunstmaan\AdminListBundle\Controller\AdminListController;
use Kunstmaan\AdminListBundle\AdminList\Configurator\AdminListConfiguratorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * The admin list controller for Rumble
 */
class RumbleAdminListController extends AdminListController
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
            $this->configurator = new RumbleAdminListConfigurator($this->getEntityManager());
        }

        return $this->configurator;
    }

    /**
     * The index action
     *
     * @Route("/", name="cartoonbattlebundle_admin_rumble_rumble")
     * @param Request $request
     * @return array
     */
    public function indexAction(Request $request)
    {
        $this->denyAccessUnlessGranted('ROLE_RUMBLES');

        return parent::doIndexAction($this->getAdminListConfigurator(), $request);
    }

    /**
     * The add action
     *
     * @Route("/add", name="cartoonbattlebundle_admin_rumble_rumble_add")
     * @Method({"GET", "POST"})
     * @param Request $request
     * @return array
     */
    public function addAction(Request $request)
    {
        $this->denyAccessUnlessGranted('ROLE_RUMBLES');

        return parent::doAddAction($this->getAdminListConfigurator(), null, $request);
    }

    /**
     * The edit action
     *
     * @param Request $request
     * @param int $id
     * @return array|Response
     * @Route("/{id}", requirements={"id" = "\d+"}, name="cartoonbattlebundle_admin_rumble_rumble_edit")
     * @Method({"GET", "POST"})
     *
     */
    public function editAction(Request $request, $id)
    {
        $this->denyAccessUnlessGranted('ROLE_RUMBLES');

        return parent::doEditAction($this->getAdminListConfigurator(), $id, $request);
    }

    /**
     * The edit action
     *
     * @param Request $request
     * @param int $id
     * @return Response
     * @Route("/{id}", requirements={"id" = "\d+"}, name="cartoonbattlebundle_admin_rumble_rumble_view")
     * @Method({"GET"})
     *
     */
    public function viewAction(Request $request, $id)
    {
        return parent::doViewAction($this->getAdminListConfigurator(), $id, $request);
    }

    /**
     * The delete action
     *
     * @param Request $request
     * @param int $id
     * @return array|Response
     * @Route("/{id}/delete", requirements={"id" = "\d+"}, name="cartoonbattlebundle_admin_rumble_rumble_delete")
     * @Method({"GET", "POST"})
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $this->denyAccessUnlessGranted('ROLE_RUMBLES');

        return parent::doDeleteAction($this->getAdminListConfigurator(), $id, $request);
    }

}
