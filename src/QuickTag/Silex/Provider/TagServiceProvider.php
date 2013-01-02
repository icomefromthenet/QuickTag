<?php
namespace QuickTag\Silex\Provider;

use Silex\Application,
    Silex\ServiceProviderInterface;

use DBALGateway\Metadata\Table;

use QuickTag\Silex\Controllers\TagProvider,
    QuickTag\QuickTagException;

/**
  *  Service provider to start tag storage for silex.
  *
  *  @author Lewis Dyer <getintouch@icomefromthenet.com>
  *  @since 0.0.1
  */
class TagServiceProvider implements ServiceProviderInterface
{
    /**
      *  @var string the index to place the Tag Instance at 
      */    
    protected $index;
    
    /**
    * Class Constructor
    *
    * @access public
    * @param string $index
    */
    public function __construct($index)
    {
        $this->index = $index;
    }
    
    
    /**
    *  Register the setup code with the DI Container
    *
    * @access public
    * @param Application $app
    */    
    public function register(Application $app)
    {
        $app[$this->index.'.options'] = array(); 
        $index                        = $this->index;
        
        #------------------------------------------------------------------
        # Table Meta Data
        #
        #------------------------------------------------------------------
       
        $app[$this->index. '.meta'] = $app->share(function() use ($app,$index) {
            
            $table = new Table($app[$index.'.options']['tableName']);
        
            $table->addColumn('tag_id'          ,'integer' ,array("unsigned" => true,'autoincrement' => true));
            $table->addColumn('tag_user_context','integer' ,array("unsigned" => true,'notnull' => false));
            $table->addColumn('tag_date_created','datetime',array('notnull' => true));
            $table->addColumn('tag_weight'      ,'float'   ,array("unsigned" => true,'notnull' => false));
            $table->addColumn('tag_title'       ,'string'  ,array('length'=> 45,'notnull' => true));
            $table->setPrimaryKey(array("tag_id"));
            $table->addIndex(array('tag_user_context'));
            
            # vcolumn for counts
            $table->addVirtualColumn('tag_count','integer',array('unsigned' => true));
            
            return $table;
        
        });
        
       
        #------------------------------------------------------------------
        # Load the Tag Library API
        #
        #------------------------------------------------------------------
        
        
        $app[$this->index] = $app->share(function() use ($app,$index) {
            
            # merge options with default struct
            $app[$index.'.options'] = array_replace(
                array(
                    'tableName'          => 'quicktag_tags',
                ), $app[$index.'.options']
            );
            
            
            $event     = $app['dispatcher'];
            $monolog   = $app['monolog'];
            $db        = $app['db'];
            $meta      = $app[$index.'.meta'];
            $tableName = $app[$index.'.options']['tableName'];
            
            $logBridge     = new \QuickTag\Log\MonologBridge($monolog);
            $logSubscriber = new \QuickTag\Log\LogSubscriber($logBridge);
            $tagBuilder    = new \QuickTag\Model\TagBuilder();
            
            # bind events to logbridge
            $event->addSubscriber($logSubscriber);
            
            $tagGateway = new \QuickTag\Model\TagGateway($tableName,$db,$event,$meta,null,$tagBuilder);
            $tagMapper  = new \QuickTag\Model\TagMapper($event,$tagGateway);
            
            return new \QuickTag\Tag($event,$tagMapper);   
                
        });
        
        
        #------------------------------------------------------------------
        # Load the Entity Formatters
        #
        #------------------------------------------------------------------
       
        $app[$this->index.'.tagFormatter'] = $app->share(function()  {
                return new \QuickTag\Silex\Formatter\TagFormatter();
        });
                
        
       
    }

    public function boot(Application $app)
    {
        
    }
}
/* End of File */