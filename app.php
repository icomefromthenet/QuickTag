<?php
# ----------------------------------------------------
# Include Composer  Autoloader
# 
# ---------------------------------------------------

require_once(__DIR__ . "/vendor/autoload.php");


# ----------------------------------------------------
# Create the application
# 
# ---------------------------------------------------


$app = new Silex\Application();


#------------------------------------------------------------------
# Add Parse for json requests body
#
#------------------------------------------------------------------

$app->before(function (Symfony\Component\HttpFoundation\Request $request) {
    if (0 === strpos($request->headers->get('Content-Type'), 'application/json')) {
        $data = json_decode($request->getContent(), true);
        $request->request->replace(is_array($data) ? $data : array());
    }
});

#------------------------------------------------------------------
# Load the Configuration
#
#------------------------------------------------------------------

require (__DIR__ . "/resources/config/application.php");


# ----------------------------------------------------
# Load ValidatorServiceProvider
# 
# ---------------------------------------------------

$app->register(new Silex\Provider\ValidatorServiceProvider());



# ----------------------------------------------------
# Setup Tags
# 
# ---------------------------------------------------

$app->register(new QuickTag\Silex\Provider\TagServiceProvider('qtag'), array(
                                'qtag.options' => array(
                                      'tableName' => 'quicktag_tags'  
                                )
              ));

return $app;