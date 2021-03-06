<?php
namespace QuickTag;

use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Doctrine\DBAL\Connection;
use DBALGateway\Metadata\Table;

use QuickTag\Log\LogInterface;
use QuickTag\Log\LogSubscriber;
use QuickTag\Model\TagBuilder;
use QuickTag\Model\TagGateway;
use QuickTag\Model\TagMapper;

class TagServiceProvider
{
    
    protected function getDatabseMetaData($tableName)
    {
        $table = new Table($tableName);
        
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
    }
    
    
    public function instance(Connection $db, EventDispatcherInterface $event, LogInterface $logBridge, $tableName)
    {
        # bind events to logbridge
        $event->addSubscriber(new LogSubscriber($logBridge));
        
        # create an API instance
        return new Tag($event,new TagMapper($event,new TagGateway($tableName,$db,$event,$this->getDatabseMetaData($tableName),null,new TagBuilder())));
    }
    
}
/* End of File */