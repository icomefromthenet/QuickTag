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
class TagRemoveEvent extends Event
{
    /**
      *  @var Doctrine\Common\Collections\Collection 
      */
    protected $result;

    /**
      *  @var  QuickTag\Model\StoredTag
      */
    protected $removedTag;
    
    /**
      *  Class Constructor
      *
      *  @access public
      */
    public function __construct($removed,StoredTag $removedTag)
    {
        $this->result     = (boolean) $removed;
        $this->removedTag = $removedTag;
    }

   
    /**
      *  Return the result of remove operation
      *
      *  @access public
      *  @return boolean true if removed
      */    
    public function getResult()
    {
        return $this->result;             
    }
    
    
    /**
      *  Return the removed tag
      *
      *  @access public
      *  @return QuickTag\Model\StoredTag
      */
    public function getRemovedTag()
    {
        return $this->removedTag;
    }
    
    
}
/* End of File */