<?php
namespace QuickTag\Tests;

use PHPUnit_Framework_TestCase;
use DateTime;
use Doctrine\Common\Collections\Collection;
use QuickTag\Events\TagLookupEvent;
use QuickTag\Events\TagRemoveEvent;
use QuickTag\Events\TagStoreEvent;
use QuickTag\Model\StoredTag;

/**
  *  Unit Tests for Event objects
  *
  *  @author Lewis Dyer <getintouch@icomefromthenet.com>
  *  @since 0.0.1
  */
class EventTest extends PHPUnit_Framework_TestCase
{
    
    public function testLookupEventProperties()
    {
        $collection = $this->getMock('Doctrine\Common\Collections\Collection');
        
        $event = new TagLookupEvent($collection);
        
        $this->assertEquals($collection,$event->getResult());
        
    }
    
    
    public function testStoreEventProperties()
    {
        $tag    = $this->getMock('QuickTag\Model\StoredTag');
        $result = true;
        
        $event = new TagStoreEvent($result,$tag);
        
        $this->assertEquals($result,$event->getResult());
        $this->assertEquals($tag,$event->getStoredTag());
        
    }
    
    
    public function testRemoveEventProperties()
    {
        $tag = $this->getMock('QuickTag\Model\StoredTag');   
        $result = true;
    
        $event = new TagRemoveEvent($result,$tag);
        
        $this->assertEquals($tag,$event->getRemovedTag());
        $this->assertEquals($result,$event->getResult());
    }
    
    
    
}
/* End of File */