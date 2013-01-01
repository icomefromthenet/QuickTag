<?php
namespace QuickTag\Tests;

use QuickTag\Log\LogSubscriber;
use QuickTag\Log\LogInterface;
use QuickTag\Model\StoredTag;
use Doctrine\Common\Collections\ArrayCollection;
use QuickTag\Events\TagLookupEvent;
use QuickTag\Events\TagRemoveEvent;
use QuickTag\Events\TagStoreEvent;
use PHPUnit_Framework_TestCase;
use DateTime;

/**
  *  Unit Tests for Log Subscriber Bridge
  *
  *  @author Lewis Dyer <getintouch@icomefromthenet.com>
  *  @since 0.0.1
  */
class LogSubscriberTest extends PHPUnit_Framework_TestCase
{
    
    public function testLookupLog()
    {
        $logger = $this->getMock('QuickTag\Log\LogInterface');
        $sub    = new LogSubscriber($logger);
        $tag    = new StoredTag();
        $collection = new ArrayCollection();
        $collection->add($tag);
            
        $logger->expects($this->once())
               ->method('info')
               ->with('QuickTag:: Looking up 1 tags',array());
        
        # run the log handler
        $sub->logLookupEvent(new TagLookupEvent($collection));
        
    }
    
    public function testLookupLogNoEntities()
    {
        $logger = $this->getMock('QuickTag\Log\LogInterface');
        $sub    = new LogSubscriber($logger);
        $tag    = new StoredTag();
        $collection = new ArrayCollection();
            
        $logger->expects($this->exactly(0))
               ->method('info');
        
        # run the log handler
        $sub->logLookupEvent(new TagLookupEvent($collection));
        
    }
    
    
    public function testlogRemoveEvent()
    {
        $logger = $this->getMock('QuickTag\Log\LogInterface');
        $sub    = new LogSubscriber($logger);
        
        $tag    = new StoredTag();
        $tag->setTagId(1);
        $tag->setTitle('aaaa');
        
        $logger->expects($this->once())
               ->method('info')
               ->with('QuickTag:: Remove tag at ID 1 with title aaaa',array());
        
        # run the log handler
        $sub->logRemoveEvent(new TagRemoveEvent(true,$tag));
        
    }
    
    public function testlogRemoveEventFailedRemoval()
    {
        $logger = $this->getMock('QuickTag\Log\LogInterface');
        $sub    = new LogSubscriber($logger);
        
        $tag    = new StoredTag();
        $tag->setTagId(1);
        $tag->setTitle('aaaa');
        
        $logger->expects($this->once())
               ->method('info')
               ->with('QuickTag:: Failed to remove tag at ID 1 with title aaaa',array());
        
        # run the log handler
        $sub->logRemoveEvent(new TagRemoveEvent(false,$tag));
        
    }
    
    
    public function testlogStoreEvent()
    {
        $logger = $this->getMock('QuickTag\Log\LogInterface');
        $sub    = new LogSubscriber($logger);
        
        $tag    = new StoredTag();
        $tag->setTagId(1);
        $tag->setTitle('aaaa');
        
        $logger->expects($this->once())
               ->method('info')
               ->with('QuickTag:: Stored Tag at ID 1 with title aaaa',array());
        
        # run the log handler
        $sub->logStoreEvent(new TagStoreEvent(true,$tag));
        
    }
    
    
     public function testlogStoreEventFailedStorage()
    {
        $logger = $this->getMock('QuickTag\Log\LogInterface');
        $sub    = new LogSubscriber($logger);
        
        $tag    = new StoredTag();
        $tag->setTagId(1);
        $tag->setTitle('aaaa');
        
        $logger->expects($this->once())
               ->method('info')
               ->with('QuickTag:: Failed to stored Tag at ID 1 with title aaaa',array());
        
        # run the log handler
        $sub->logStoreEvent(new TagStoreEvent(false,$tag));
        
    }
}
/* End of File */