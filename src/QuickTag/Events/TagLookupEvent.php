<?php
namespace QuickTag\Events;

use Symfony\Component\EventDispatcher\Event;
use Doctrine\Common\Collections\Collection;

/**
  *  Event that occurs after Tag set is queried (single or group)
  *
  *  @author Lewis Dyer <getintouch@icomefromthenet.com>
  *  @since 0.0.1
  */
class TagLookupEvent extends Event
{
    /**
      *  @var Doctrine\Common\Collections\Collection 
      */
    protected $result;

    /**
      *  Class Constructor
      *
      *  @access public
      */
    public function __construct(Collection $collection)
    {
        $this->result = $collection;
    }

   
    /**
      *  Return the result collection
      *
      *  @access public
      *  @return Doctrine\Common\Collections\Collection
      */    
    public function getResult()
    {
        return $this->result;             
    }
    
    
}
/* End of File */