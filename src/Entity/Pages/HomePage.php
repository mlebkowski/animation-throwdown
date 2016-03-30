<?php

namespace ArsThanea\Acme\Entity\Pages;

use ArsThanea\Syzygy\SyzygyBundle;
use Kunstmaan\NodeBundle\Entity\AbstractPage;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="home_pages")
 */
class HomePage extends AbstractPage
{

}
