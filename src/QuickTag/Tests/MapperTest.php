<?php
namespace QuickTag\Tests;

use DateTime;
use QuickTag\Model\TagMapper;
use QuickTag\Model\TagGateway;
use QuickTag\Model\TagQuery;
use QuickTag\Model\TagBuilder;
use QuickTag\Model\StoredTag;
use QuickTag\Tests\TestsWithFixture;


class MapperTest extends TestsWithFixture
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
    
    
    public function testFindById()
    {
        $gateway = $this->getTableGateway();
        $event   = $this->getMock('Symfony\Component\EventDispatcher\EventDispatcherInterface');
        
        $mapper = new TagMapper($event,$gateway);
        
        $result = $mapper->findByID(100);
        
        $this->assertInstanceOf('QuickTag\Model\StoredTag',$result);
        $this->assertEquals(100,$result->getTagId());
        $this->assertEquals(1,$result->getUserContext());
        $this->assertEquals(79,$result->getWeight());
        $this->assertEquals('gdud0',$result->getTitle());
        $this->assertInstanceOf('DateTime',$result->getTagCreated());
        
    }
    
    
    
    public function testFindByIdNoneFound()
    {
        $gateway = $this->getTableGateway();
        $event   = $this->getMock('Symfony\Component\EventDispatcher\EventDispatcherInterface');
        $mapper = new TagMapper($event,$gateway);
        
        $result = $mapper->findByID(101);
        
        $this->assertEquals(null,$result);
    }
    
    
    public function testMapperFind()
    {
        $gateway = $this->getTableGateway();
        $event   = $this->getMock('Symfony\Component\EventDispatcher\EventDispatcherInterface');
        $mapper = new TagMapper($event,$gateway);
        
        $this->assertInstanceOf('DBALGateway\Container\SelectContainer',$mapper->find());
        
        $result = $mapper->find()
            ->where()
                ->limit(10)
            ->end()
        ->find();
        
        $this->assertEquals(10,$result->count());
        $this->assertInstanceOf('Doctrine\Common\Collections\Collection',$result);
        
    }
    
    public function testDelete()
    {
        $gateway = $this->getTableGateway();
        $event   = $this->getMock('Symfony\Component\EventDispatcher\EventDispatcherInterface');
        $mapper = new TagMapper($event,$gateway);
        
        $tag    = new StoredTag();
        $tag->setTagId(1);
        
        $result = $mapper->delete($tag);
        
        $this->assertTrue($result);
        $this->assertEquals(0,$tag->getTagId());
        
    }
    
    public function testDeleteInvalidId()
    {
        $gateway = $this->getTableGateway();
        $event   = $this->getMock('Symfony\Component\EventDispatcher\EventDispatcherInterface');
        $mapper = new TagMapper($event,$gateway);
        
        $tag    = new StoredTag();
        $tag->setTagId(1000000);
        
        $result = $mapper->delete($tag);
        
        $this->assertFalse($result);
        $this->assertEquals(1000000,$tag->getTagId());
        
    }
    
    
    public function testSaveUpdate()
    {
        $gateway = $this->getTableGateway();
        $event   = $this->getMock('Symfony\Component\EventDispatcher\EventDispatcherInterface');
        $mapper = new TagMapper($event,$gateway);
        
        $tag    = new StoredTag();
        $tag->setTagId(1);
        $tag->setUserContext(2);
        $tag->setTitle('aaaa');
        $tag->setWeight(10.6);
        
        $result = $mapper->save($tag);
        
        $this->assertTrue($result);
        $this->assertEquals(1,$tag->getTagId());
        
    }
    
    public function testSaveUpdateNoChanges()
    {
        $gateway = $this->getTableGateway();
        $event   = $this->getMock('Symfony\Component\EventDispatcher\EventDispatcherInterface');
        $mapper = new TagMapper($event,$gateway);
        
        $tag    = new StoredTag();
        $tag->setTagId(1);
        $tag->setUserContext(1);
        $tag->setTitle('rwod4');
        $tag->setWeight(1);
        
        $result = $mapper->save($tag);
        
        $this->assertFalse($result);
        $this->assertEquals(1,$tag->getTagId());
        
    }
    
    
    public function testSaveCreate()
    {
        $gateway = $this->getTableGateway();
        $event   = $this->getMock('Symfony\Component\EventDispatcher\EventDispatcherInterface');
        $mapper = new TagMapper($event,$gateway);
        
        $tag    = new StoredTag();
        $tag->setUserContext(1);
        $tag->setTitle('rwod4');
        $tag->setWeight(1);
        
        $result = $mapper->save($tag);
        
        $this->assertTrue($result);
        $this->assertEquals(101,$tag->getTagId());
        $this->assertInstanceof('\DateTime',$tag->getTagCreated());  
        
    }
    
    /**
      *  @expectedException QuickTag\QuickTagException
      *  @expectedExceptionMessage SQLSTATE[23000]: Integrity constraint violation: 1048 Column 'tag_title' cannot be null
      */
    public function testSaveWithError()
    {
        $gateway = $this->getTableGateway();
        $event   = $this->getMock('Symfony\Component\EventDispatcher\EventDispatcherInterface');
        $mapper = new TagMapper($event,$gateway);
        
        $tag    = new StoredTag();
        $tag->setUserContext(1);
        $tag->setWeight(1);
        
        $result = $mapper->save($tag);
        
        
    }
    
    
}    
/* End of File */