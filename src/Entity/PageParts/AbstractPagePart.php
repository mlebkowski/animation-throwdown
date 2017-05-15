<?php

namespace Nassau\CartoonBattle\Entity\PageParts;

use ArsThanea\KunstmaanExtraBundle\Page\KunstmaanExtraPagePart;

abstract class AbstractPagePart extends KunstmaanExtraPagePart
{
    public function getBundleName()
    {
        return 'Acme';
    }

}
