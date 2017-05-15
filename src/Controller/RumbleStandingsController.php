<?php

namespace Nassau\CartoonBattle\Controller;

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
        $serializer = $this->get('serializer');
        $rumbles = $this->get('doctrine.orm.entity_manager')->getRepository('CartoonBattleBundle:Rumble\Rumble')->findAll();


        $format = $request->getRequestFormat();

        return new Response($serializer->serialize($rumbles, $format));
    }
}
