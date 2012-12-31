<?php
namespace QuickTag\Events;

use Symfony\Component\EventDispatcher\Event;
use QuickTag\Model\StoredTag;

/**
  *  Event that occurs after Tag has been removed
  *
  *  @author Lewis Dyer <getintouch@icomefromthenet.com>
  *  @since 0.0.1
  */
class TagStoreEvent extends Event
{
    /**
      *  @var Doctrine\Common\Collections\Collection 
      */
    protected $result;

    /**
      *  @var  QuickTag\Model\StoredTag
      */
    protected $storedTag;
    
    /**
      *  Class Constructor
      *
      *  @access public
      */
    public function __construct($stored,StoredTag $storedTag)
    {
        $this->result     = (boolean) $stored;
        $this->storedTag = $storedTag;
    }

   
    /**
      *  Return the result of the store operation
      *
      *  @access public
      *  @return boolean true if stored
      */    
    public function getResult()
    {
        return $this->result;             
    }
    
    
    /**
      *  Return the stored tag
      *
      *  @access public
      *  @return QuickTag\Model\StoredTag
      */
    public function getStoredTag()
    {
        return $this->storedTag;
    }
    
    
}
/* End of File */