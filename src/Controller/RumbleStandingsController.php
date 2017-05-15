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

        return new Response($serializer->serialize($rumbles, $format), Response::HTTP_OK, [
            'Access-Control-Allow-Credentials' => 'true',
            'Access-Control-Allow-Methods' => 'POST, GET, PUT, DELETE, PATCH, OPTIONS',
            'Access-Control-Allow-Headers' => 'Origin, Content-Type, Accept, Authorization',
            'Access-Control-Allow-Origin' => $request->headers->get('origin'),
            'Access-Control-Max-Age' => 3600,
            'Vary' => 'Origin',
        ]);
    }
}
