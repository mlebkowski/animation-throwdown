<?php

namespace Nassau\CartoonBattle\Controller;

use Kunstmaan\AdminListBundle\AdminList\Configurator\AbstractAdminListConfigurator;
use Nassau\CartoonBattle\AdminList\UnitAdminListConfigurator;
use Kunstmaan\AdminListBundle\Controller\AdminListController;
use Kunstmaan\AdminListBundle\AdminList\Configurator\AdminListConfiguratorInterface;
use Nassau\CartoonBattle\Entity\Unit;
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
     * @param Request $request
     * @return array
     */
    public function indexAction(Request $request)
    {
        $this->denyAccessUnlessGranted('ROLE_CARDS');

        return parent::doIndexAction($this->getAdminListConfigurator(), $request);
    }

    /**
     * The edit action
     *
     * @param Request $request
     * @param Unit $unit
     * @return array|Response
     * @Route("/{id}", requirements={"id" = "\d+"}, name="cartoonbattlebundle_admin_unit_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Unit $unit)
    {
        $this->denyAccessUnlessGranted('ROLE_CARDS');

        if ($request->get('find-image')) {
            $image = $this->get('doctrine.orm.entity_manager')
                ->createQueryBuilder()
                ->select('media')
                ->from('KunstmaanMediaBundle:Media', 'media')
                ->where('media.name = :name')
                ->andWhere('media.deleted = false')
                ->setParameter('name', $unit->getPicture() . '.png')
                ->getQuery()
                ->getOneOrNullResult();

            if ($image) {
                $this->addFlash('success', 'Image found in the library. Click save to confirm');
                $unit->setImage($image);
            } else {
                $this->addFlash('warning', 'No image found by that name');
            }
        }

        return parent::doEditAction($this->getAdminListConfigurator(), $unit->getId(), $request);
    }

    /**
     * The edit action
     *
     * @param Request $request
     * @param int $id
     * @return array
     * @Route("/{id}", requirements={"id" = "\d+"}, name="cartoonbattlebundle_admin_unit_view")
     * @Method({"GET"})
     *
     */
    public function viewAction(Request $request, $id)
    {
        $this->denyAccessUnlessGranted('ROLE_CARDS');

        return parent::doViewAction($this->getAdminListConfigurator(), $id, $request);
    }

}
