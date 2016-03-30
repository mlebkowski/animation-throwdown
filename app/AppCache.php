<?php

use Symfony\Bundle\FrameworkBundle\HttpCache\HttpCache;

class AppCache extends HttpCache
{
    protected function getOptions()
    {
        return [
            'allow_reload' => (bool)getenv('HTTP_ALLOW_RELOAD'),
            'allow_revalidate' => (bool)getenv('HTTP_ALLOW_REVALIDATE'),
        ];
    }
}
