<?php

namespace Nassau\CartoonBattle\Controller;

use Nassau\CartoonBattle\Services\Request\CorsResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class RumbleStandingsController extends Controller
{
    /**
     * @Route(name="rumble_standings", path="/rumble-standings.{_format}", requirements={ "_format": "json|xml"})
     * @param Request $request
     * @return Response
     */
    public function indexAction(Request $request)
    {
        $serializer = $this->get('jms_serializer');
        $rumbles = $this->get('doctrine.orm.entity_manager')->getRepository('CartoonBattleBundle:Rumble\Rumble')->findAll();

        return new CorsResponse($serializer->toArray($rumbles), $request);
    }
}
