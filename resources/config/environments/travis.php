<?php
/*
 * Development Config File
 *
 */

# ----------------------------------------------------
# Set Silex to Debug mode
# 
# ---------------------------------------------------

$app["debug"] = true;


# ----------------------------------------------------
# Set Monolog
# ---------------------------------------------------

$app->register(new Silex\Provider\MonologServiceProvider(), array(
    'monolog.logfile' => $app['cache_path'].'logs/quicktag.log',
));

# ----------------------------------------------------
# Log queries into a file
# ---------------------------------------------------

$app->before(function() use ($app) {
   
   $logger = new DBALGateway\Feature\StreamQueryLogger($app['monolog']);
   
   $app['dispatcher']->addSubscriber($logger);
    
});
# ----------------------------------------------------
# Setup Database and PDOSessionHandler
# 
# ---------------------------------------------------

$app->register(new Silex\Provider\DoctrineServiceProvider(), array(
    'db.options' => array(
        'driver'    => 'pdo_mysql',
        'host'      => 'localhost',
        'dbname'    => 'quick_tag',
        'user'      => 'root',
        'password'  => '',
    )
));
