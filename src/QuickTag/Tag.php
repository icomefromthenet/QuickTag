<?php
namespace QuickTag;

use DateTime;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use QuickTag\Model\TagMapper,
    QuickTag\Model\StoredTag,
    QuickTag\Events\TagEventsMap,
    QuickTag\Events\TagLookupEvent,
    QuickTag\Events\TagRemoveEvent,
    QuickTag\Events\TagStoreEvent;   

/**
  *  Tag Storage API, Store your tags in a database
  *
  *  @author Lewis Dyer <getintouch@icomefromthenet.com>
  *  @since 0.0.1
  */
class Tag
{
    /**
      *  @var Symfony\Component\EventDispatcher\EventDispatcherInterface  
      */
    protected $event;
    
    /**
      *  @var  QuickTag\Model\TagMapper
      */
    protected $mapper;    
    
    
    /**
      *  Class Constructor
      *
      *  @access public
      *  @param EventDispatcherInterface $event
      *  @param TagMapper $mapper
      */
    public function __construct(EventDispatcherInterface $event, TagMapper $mapper)
    {
        $this->event   = $event;
        $this->mapper  = $mapper;
    }
    
    
    /**
      *  Find a tag by the ID
      *
      *  Note : LookupEvent is fired by the model not this api
      *
      *  @access public
      *  @return QuickTag\Model\StoredTag or null if none found
      *  @param integer $id
      *  @throws QuickTag\QuickTagException if database operation fails
      */
    public function lookupTag($id)
    {
        if(is_int($id) === false) {
            throw new QuickTagException('Tag Id must be an integer');
        }
        
        return $this->mapper->findByID($id);
    }
    
    
    /**
      *  Store a new or existing tag
      *
      *  @access public
      *  @return boolean the result true if sucessful
      *  @param QuickTag\Model\StoredTag $tag
      *  @throws QuickTag\QuickTagException if database operation fails
      */
    public function storeTag(StoredTag $tag)
    {
        $result = $this->mapper->save($tag);
        
        $this->event->dispatch(TagEventsMap::STORE,new TagStoreEvent($result,$tag));
        
        return $result;
        
    }
    
    /**
      *  Remove a tag
      *
      *  @access public
      *  @return boolean the result true if removed
      *  @param QuickTag\Model\StoredTag $tag
      *  @throws QuickTag\QuickTagException if database operation fails
      */
    public function removeTag(StoredTag $tag)
    {
        $result = $this->mapper->delete($tag);
        
        $this->event->dispatch(TagEventsMap::REMOVE,new TagRemoveEvent($result,$tag));
        
        return $result;
        
    }
    
    /**
      *  Create a tag query
      *
      *  Note : LookupEvent is fired by the model not this api
      *
      *  @access public
      *  @return DBALGateway\Container\SelectContainer
      */
    public function findTag()
    {
        return $this->mapper->find();              
    }
    
    
}
/* End of File */