<?php

namespace Nassau\CartoonBattle\Controller;

use Nassau\CartoonBattle\Entity\Game\Enemy;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class HarvestingController extends Controller
{
    public function deckAction(Enemy $enemy)
    {
        return $this->render('CartoonBattleBundle:Harvesting:Deck.html.twig', [
            'enemy' => $enemy,
        ]);
    }
}
