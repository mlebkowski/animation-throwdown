<?php

namespace Nassau\CartoonBattle\Entity\Pages;

use ArsThanea\KunstmaanExtraBundle\Page\KunstmaanExtraPage;

abstract class AbstractPage extends KunstmaanExtraPage
{
    protected function getBundleName()
    {
        return 'Acme';
    }

}
