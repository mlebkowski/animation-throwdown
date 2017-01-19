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
$request = Request::createFromGlobals();
Request::setTrustedProxies([$request->server->get('REMOTE_ADDR'), '127.0.0.1']);

$isBehindCache = $request->headers->has('X-ProxyCache') || false !== strpos($request->headers->get('Surrogate-Capability', ''), 'ESI/1.0');
if (false === $isBehindCache) {
    $kernel = new AppCache($kernel);
}

$response = $kernel->handle($request);

//
// we don’t need the chunked transfer encoding if behind cache;
// also, if PHP sends content larger than one buffer, Apache
// turns on the chunked transfer encoding and disables Content-Length
// header -- this in turn messes up the CloudFront automatic gzipping
//
// http://serverfault.com/questions/59047/apache-sending-transfer-encoding-chunked
// http://docs.aws.amazon.com/AmazonCloudFront/latest/DeveloperGuide/ServingCompressedFiles.html
//
if ($isBehindCache && false === $response->headers->has('Transfer-Encoding')) {
    $response->headers->set('Content-Length', strlen($response->getContent()));
}

$response->send();
$kernel->terminate($request, $response);
