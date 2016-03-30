<?php

use Symfony\Component\ClassLoader\ApcClassLoader;
use Symfony\Component\HttpFoundation\Request;

/**
 * @var Composer\Autoload\ClassLoader
 */
$loader = require __DIR__.'/../app/autoload.php';
include_once __DIR__.'/../app/bootstrap.php.cache';

if (extension_loaded('apc') && ini_get('apc.enabled')) {
    // Enable APC for autoloading to improve performance.
    // You should change the ApcClassLoader first argument to a unique prefix
    // in order to prevent cache key conflicts with other applications
    // also using APC.
    $apcLoader = new ApcClassLoader(sha1(__FILE__), $loader);
    $loader->unregister();
    $apcLoader->register(true);
}

$kernel = new AppKernel('prod', false);
$kernel->loadClassCache();

if (!isset($_SERVER['HTTP_SURROGATE_CAPABILITY']) || false === strpos($_SERVER['HTTP_SURROGATE_CAPABILITY'], 'ESI/1.0')) {
    $kernel = new AppCache($kernel);
}

$request = Request::createFromGlobals();
Request::setTrustedProxies([$request->server->get('REMOTE_ADDR')]);
$response = $kernel->handle($request);
$response->send();
$kernel->terminate($request, $response);
