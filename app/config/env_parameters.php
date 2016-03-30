<?php

$dsn = getenv('CLEARDB_DATABASE_URL');
if ($dsn) {
    $db = parse_url($dsn);
    /** @var Symfony\Component\DependencyInjection\ContainerInterface $container */
    $container->setParameter('database_host', $db['host']);
    if (isset($db['port'])) {
        $container->setParameter('database_port', $db['port']);
    }
    $container->setParameter('database_name', substr($db["path"], 1));
    $container->setParameter('database_user', $db['user']);
    $container->setParameter('database_password', $db['pass']);
    unset($db);
}

$dsn = getenv('REDIS_URL');
if ($dsn) {
    $redis = parse_url($dsn) + ['pass' => null];
    $container->setParameter('redis.host', $redis['host']);
    $container->setParameter('redis.port', $redis['port']);
    $container->setParameter('redis.password', $redis['pass']);
    unset($redis);
}


unset($dsn);
