<?php
namespace QuickTag\Tests;

use QuickTag\Tag;
use QuickTag\TagServiceProvider;
use QuickTag\Model\StoredTag;
use QuickTag\Tests\TestsWithFixture;
use QuickTag\Events\TagEventsMap;

class TagTest extends TestsWithFixture
{
    
    public function testLookup()
    {
        $mock_event  = $this->getMock('Symfony\Component\EventDispatcher\EventDispatcherInterface');
        $mock_mapper = $this->getMockBuilder('QuickTag\Model\TagMapper')
                            ->disableOriginalConstructor()
                            ->getMock();
        $tag         = new Tag($mock_event,$mock_mapper);
        $stored_tag  = new StoredTag();
        
        $mock_mapper->expects($this->once())
                    ->method('findByID')
                    ->with(1)
                    ->will($this->returnValue($stored_tag));
        
        $this->assertEquals($stored_tag,$tag->lookupTag(1));
    }
    
    
    
    public function testStoreNewTag()
    {
        
        $mock_event  = $this->getMock('Symfony\Component\EventDispatcher\EventDispatcherInterface');
        $mock_mapper = $this->getMockBuilder('QuickTag\Model\TagMapper')
                            ->disableOriginalConstructor()
                            ->getMock();
        $tag         = new Tag($mock_event,$mock_mapper);
        $stored_tag  = new StoredTag();
        
        $mock_event->expects($this->once())
                   ->method('dispatch')
                   ->with(TagEventsMap::STORE,$this->isInstanceOf('QuickTag\Events\TagStoreEvent'));
        
        $mock_mapper->expects($this->once())
                    ->method('save')
                    ->with($stored_tag)
                    ->will($this->returnValue(true));
        
        $this->assertTrue($tag->storeTag($stored_tag));
        
    }
    
    
    public function testRemoveTag()
    {
        $mock_event  = $this->getMock('Symfony\Component\EventDispatcher\EventDispatcherInterface');
        $mock_mapper = $this->getMockBuilder('QuickTag\Model\TagMapper')
                            ->disableOriginalConstructor()
                            ->getMock();
        $tag         = new Tag($mock_event,$mock_mapper);
        $stored_tag  = new StoredTag();
        
        
        $mock_event->expects($this->once())
                   ->method('dispatch')
                   ->with(TagEventsMap::REMOVE,$this->isInstanceOf('QuickTag\Events\TagRemoveEvent'));
        
        $mock_mapper->expects($this->once())
                    ->method('delete')
                    ->with($stored_tag)
                    ->will($this->returnValue(true));
        
        $this->assertTrue($tag->removeTag($stored_tag));
        
        
    }
    
    
    public function testFindTag()
    {
        $mock_event  = $this->getMock('Symfony\Component\EventDispatcher\EventDispatcherInterface');
        $mock_mapper = $this->getMockBuilder('QuickTag\Model\TagMapper')
                            ->disableOriginalConstructor()
                            ->getMock();
        $tag         = new Tag($mock_event,$mock_mapper);
        $container   = $this->getMockBuilder('DBALGateway\Container\SelectContainer')
                            ->disableOriginalConstructor()
                            ->getMock();
        
        $mock_mapper->expects($this->once())
                    ->method('find')
                    ->will($this->returnValue($container));
        
        $this->assertEquals($container,$tag->findTag());
        
    }
    
    
    public function testServiceProvider()
    {
        $mockEvent      = $this->getMock('Symfony\Component\EventDispatcher\EventDispatcherInterface');
        $mockConnection = $this->getMockBuilder('Doctrine\DBAL\Connection')
                            ->disableOriginalConstructor()
                            ->getMock();
        $mockLog        = $this->getMock('QuickTag\Log\LogInterface');
        $tableName      = "quick_tags";

        $mockEvent->expects($this->once())
                  ->method('addSubscriber')
                  ->with($this->isInstanceOf('QuickTag\Log\LogSubscriber'));
        
        $serviceProvider = new TagServiceProvider();
        
        $this->assertInstanceOf('QuickTag\Tag',$serviceProvider->instance($mockConnection,$mockEvent,$mockLog,$tableName));
    }
    
}
/* End of File */