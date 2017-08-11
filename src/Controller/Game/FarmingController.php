<?php

namespace Nassau\CartoonBattle\Controller\Game;

use JMS\Serializer\Exception\UnsupportedFormatException;
use JMS\Serializer\Serializer;
use Nassau\CartoonBattle\Entity\Game\UserFarming;
use Symfony\Component\HttpFoundation\Response;

class FarmingController
{
    /**
     * @var Serializer
     */
    private $serializer;

    /**
     * @var \Twig_Environment
     */
    private $twig;

    public function __construct(Serializer $serializer, \Twig_Environment $twig)
    {
        $this->serializer = $serializer;
        $this->twig = $twig;
    }


    public function farming(UserFarming $farming, $_format)
    {
        try {
            $data = $this->serializer->serialize($farming, $_format);
        } catch (UnsupportedFormatException $e) {
            // fallback to HTML
            $data = $this->twig->render('CartoonBattleBundle:Farming:Farming.html.twig', [
                'farming' => $farming,
            ]);
        }

        return new Response($data);
    }
}
