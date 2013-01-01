<?php
namespace QuickTag\Tests;

use DateTime;
use QuickTag\Model\TagGateway;
use QuickTag\Model\TagBuilder;
use QuickTag\Tests\TestsWithFixture;
use QuickTag\Events\TagEventsMap;

class TagGatewayTest extends TestsWithFixture
{
    
    /**
      *  Fetches a new insance of the gateway
      *
      *  @return QuickTag\Model\TagGateway
      */   
    protected function getTableGateway()
    {
        $doctrine   = $this->getDoctrineConnection();   
        $metadata   = $this->getTableMetaData(); 
        $table_name = 'quicktag_tags';
        $builder    = new TagBuilder();
        $mock_event = $this->getMock('Symfony\Component\EventDispatcher\EventDispatcherInterface');      
        
        return new TagGateway($table_name,$doctrine,$mock_event,$metadata,null,$builder);
        
    }
    
    
    public function testFindOneRaisesEvent()
    {
        $doctrine   = $this->getDoctrineConnection();   
        $metadata   = $this->getTableMetaData(); 
        $table_name = 'quicktag_tags';
        $builder    = new TagBuilder();
        $mock_event = $this->getMock('Symfony\Component\EventDispatcher\EventDispatcherInterface');      
        
        $gateway    = new TagGateway($table_name,$doctrine,$mock_event,$metadata,null,$builder);
        
        # the third event fired after gateway pre.select and post.select
        $mock_event->expects($this->at(2))
                   ->method('dispatch')
                   ->with(TagEventsMap::LOOKUP,$this->isInstanceOf('QuickTag\Events\TagLookupEvent'));
        
        # execute the query 
        $result = $gateway->selectQuery()->start()->end()->findOne();
        
    }
    
    
    public function testFindOneRaisesEventNoneFound()
    {
        $doctrine   = $this->getDoctrineConnection();   
        $metadata   = $this->getTableMetaData(); 
        $table_name = 'quicktag_tags';
        $builder    = new TagBuilder();
        $mock_event = $this->getMock('Symfony\Component\EventDispatcher\EventDispatcherInterface');      
        
        $gateway    = new TagGateway($table_name,$doctrine,$mock_event,$metadata,null,$builder);
        
        # the third event fired after gateway pre.select and post.select
        $mock_event->expects($this->at(2))
                   ->method('dispatch')
                   ->with(TagEventsMap::LOOKUP,$this->isInstanceOf('QuickTag\Events\TagLookupEvent'));
        
        # execute the query 
        $result = $gateway->selectQuery()->start()->filterById(1000000)->end()->findOne();
        
    }
    
    
    
    public function testFindOneReturnsEntity()
    {
        $doctrine   = $this->getDoctrineConnection();   
        $metadata   = $this->getTableMetaData(); 
        $table_name = 'quicktag_tags';
        $builder    = new TagBuilder();
        $mock_event = $this->getMock('Symfony\Component\EventDispatcher\EventDispatcherInterface');      
        
        $gateway    = new TagGateway($table_name,$doctrine,$mock_event,$metadata,null,$builder);
        
        # execute the query 
        $result = $gateway->selectQuery()->start()->end()->findOne();
        
        $this->assertInstanceOf('QuickTag\Model\StoredTag',$result);
        $this->assertEquals(1,$result->getTagId());
        $this->assertEquals(1,$result->getUserContext());
        $this->assertEquals(1,$result->getWeight());
        $this->assertEquals('rwod4',$result->getTitle());
        $this->assertInstanceOf('DateTime',$result->getTagCreated());
        
    }
    
    
    public function testFindRaisesEvent()
    {
         $doctrine   = $this->getDoctrineConnection();   
        $metadata   = $this->getTableMetaData(); 
        $table_name = 'quicktag_tags';
        $builder    = new TagBuilder();
        $mock_event = $this->getMock('Symfony\Component\EventDispatcher\EventDispatcherInterface');      
        
        $gateway    = new TagGateway($table_name,$doctrine,$mock_event,$metadata,null,$builder);
        
        # the third event fired after gateway pre.select and post.select
        $mock_event->expects($this->at(2))
                   ->method('dispatch')
                   ->with(TagEventsMap::LOOKUP,$this->isInstanceOf('QuickTag\Events\TagLookupEvent'));
        
        # execute the query 
        $result = $gateway->selectQuery()->start()->end()->find();
        
    }
    
    
    public function testFindRaisesEventNoneFound()
    {
         $doctrine   = $this->getDoctrineConnection();   
        $metadata   = $this->getTableMetaData(); 
        $table_name = 'quicktag_tags';
        $builder    = new TagBuilder();
        $mock_event = $this->getMock('Symfony\Component\EventDispatcher\EventDispatcherInterface');      
        
        $gateway    = new TagGateway($table_name,$doctrine,$mock_event,$metadata,null,$builder);
        
        # the third event fired after gateway pre.select and post.select
        $mock_event->expects($this->at(2))
                   ->method('dispatch')
                   ->with(TagEventsMap::LOOKUP,$this->isInstanceOf('QuickTag\Events\TagLookupEvent'));
        
        # execute the query 
        $result = $gateway->selectQuery()->start()->filterById(1000000)->end()->find();
        
    }
    
    
    public function testFindReturnsCollection()
    {
        $doctrine   = $this->getDoctrineConnection();   
        $metadata   = $this->getTableMetaData(); 
        $table_name = 'quicktag_tags';
        $builder    = new TagBuilder();
        $mock_event = $this->getMock('Symfony\Component\EventDispatcher\EventDispatcherInterface');      
        
        $gateway    = new TagGateway($table_name,$doctrine,$mock_event,$metadata,null,$builder);
        
        # execute the query 
        $result = $gateway->selectQuery()->start()->filterById(1)->end()->find();
        
        $this->assertInstanceOf('Doctrine\Common\Collections\Collection',$result);
        
        $this->assertEquals(1,$result[0]->getTagId());
        $this->assertEquals(1,$result[0]->getUserContext());
        $this->assertEquals(1,$result[0]->getWeight());
        $this->assertEquals('rwod4',$result[0]->getTitle());
        $this->assertInstanceOf('DateTime',$result[0]->getTagCreated());
        
    }
    
}    
/* End of File */
