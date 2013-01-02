#QuickTag - Tags for your application.

[![Build Status](https://travis-ci.org/icomefromthenet/QuickTag.png?branch=master)](https://travis-ci.org/icomefromthenet/QuickTag)

1. Written using Doctrine DBAL.
2. Optional Restful Silex API Supports GET/POST/PUT/DELETE.
3. Can be used with Zend/Tag/Cloud, doing tag clouds is easy.
4. PHP 5.3 and up. 

####A Tag has the following properties:
1. Title (45 character) name. no default case upper or lower fine.
2. Weight (float) used to order a set of tags
3. Created (DateTime) used to sort old and new tags
4. UserContext (integer) restrict tags to a given user id.

##Installing Use Composer

```php

    "require" : {
        "icomefromthenet/quicktag" : "dev-master",
    }
```

##Running

### Just the library.

```php

    $doctrine               = new Doctrine\DBAL\Connection;
    $symfonyEventDispatcher = new Symfony\Component\EventDispatcher\EventDispatcher();
    $monologBridge          = new QuickTag\Log\MonologBridge($monolog);  # bridget implements QuickTag\Log\LogInterface, write own bridge to change logger platform.
    $tableName              = 'quicktag_tags'; # database table name

    $provider               = new QuickTag\TagServiceProvider();
    $tagService             = $provider->instance($doctrine,$event,$logBridge,$tablename);

```


### With Silex

Inside you app.php bootstrap file.

####Requires the following external dependecies:
1. Monolog,
2. Doctrine DBAL,
3. Symfony2 Event Dispatcher


```php

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

              
#------------------------------------------------------------------
# Setup Routes / Controllers
#
#------------------------------------------------------------------

$app->mount('/', new QuickTag\Silex\Controllers\TagProvider('qtag'));


return $app;


```

If you running different instances you will need to change the index and the table name. In the example above the index is set to **qtag** . You should namespace the index with the name off your app. 

###Setup the sql for the tag table

Don't forget to change the table name!

```sql
delimiter $$

CREATE TABLE `quicktag_tags` (
  `tag_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `tag_user_context` int(10) unsigned DEFAULT NULL,
  `tag_date_created` datetime NOT NULL,
  `tag_weight` double DEFAULT NULL,
  `tag_title` varchar(45) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`tag_id`),
  KEY `IDX_FF11E9291A46B076` (`tag_user_context`)
) ENGINE=InnoDB AUTO_INCREMENT=101 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci$$

```


## The API Methods

### Store a tag Create/Update

```php
    use DateTime;
    use QuickTag\Model\StoredTag;
    
    $storedTag = new StoredTag();
    $storedTag->setTitle('mytitle');
    $storedTag->setWeight(1);
    $storedTag->setTagCreated(new DateTime());
    $storedTag->setUserContext(3);
    
    # fetch service from the provider
    $result = $tagService->storeTag($storeTag);

    if($result) {
        echo 'tag has been stored at id '. $storedTag->getTagId();
    }

```
During an update the id must be set and only the title and weight and user context can be changed.

### Lookup a tag by id.

```php
    use QuickTag\Model\StoredTag;

    # fetch service from the provider
    $storeTag = $tagService->lookupTag($id);
 
    if($storedTag instanceOf StoredTag ) {
        echo 'tag has been gound at id '. $storedTag->getTagId();
    }

```

### Searching for a tag


```php

    # Search for tags started with titte `my` and belong to user 3
    $tagCollection = $tagService->findTag()
            ->start()
                ->limit($limit)
                ->offset($offset)
                ->orderByTitle('asc')
                ->filterByNameStartsWith('my')
                ->filterByUserContext(3)
            ->end()
        ->find();
        
    if(count($tagCollection) > 0 ) {
        echo sprintf('found %s number of tags',count($tagCollection));
    }

```

### Removing a Tag.


```php
    use QuickTag\Model\StoredTag;
    
    $id = 1;
    
    # fetch service from the provider
    $storeTag = $tagService->lookupTag($id);

    $result = $tagService->removeTag($storeTag);

    if($result) {
        echo 'tag has been removed at id '. $storedTag->getTagId();
    }

```

The API under ```QuickTag\Silex\Provider\TagServiceProvider``` has basic examples on how to use the library.


### Using Zend Tag Cloud

```php
use Zend\Tag\Cloud;
use QuickTag\Model\StoredTag;

$tagA = new StoredTag();
$tagB = new StoredTag();
$tagC = new StoredTag();

$cloud = new Cloud(array(
    'tags' => array(
       $tagA,$tagB,$tagC
    )
));

// Render the cloud
echo $cloud;

```
