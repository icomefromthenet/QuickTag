<?php
namespace QuickTag\Log;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;

use QuickTag\Events\TagEventsMap,
    QuickTag\Events\TagLookupEvent,
    QuickTag\Events\TagRemoveEvent,
    QuickTag\Events\TagStoreEvent;

/**
  *  Handles events emited by the Tag Storage API to record to event log
  *
  *  @author Lewis Dyer <getintouch@icomefromthenet.com>
  *  @since 0.0.1
  */
class LogSubscriber implements EventSubscriberInterface
{
    /**
      *  @var LogInterface
      */
    protected $log;

    
    /**
      *  Bind event handlers to the dispatcher
      *
      *  @access public
      *  @static 
      *  @return array a binding to event handlers
      */
    static public function getSubscribedEvents()
    {
        return array(
            TagEventsMap::LOOKUP    => array('logLookupEvent'),
            TagEventsMap::REMOVE    => array('logRemoveEvent'),
            TagEventsMap::STORE     => array('logStoreEvent'),
        );
    }
    
    /**
      *  Class Constructor
      *
      *  @access public
      *  @param LogInterface $log
      */
    public function __construct(LogInterface $log)
    {
        $this->log = $log;
    }
    
    //------------------------------------------------------------------
    # Log Handlers
    
    /**
      *  Log lookup event
      *
      *  @access public
      *  @param TagLookupEvent $event 
      */
    public function logLookupEvent(TagLookupEvent $event)
    {
        $lookupCount = count($event->getResult());
        
        if( $lookupCount > 0) {
            $this->log->info(sprintf('QuickTag:: Looking up %s tags',$lookupCount),array());    
        } 
    }
    
    /**
      *  Log remove event
      *
      *  @access public
      *  @param TagRemoveEvent $event 
      */
    public function logRemoveEvent(TagRemoveEvent $event)
    {
        if($event->getResult() === true) {
            $this->log->info(sprintf('QuickTag:: Remove tag at ID %s with title %s',(string)$event->getRemovedTag()->getTagId(),(string)$event->getRemovedTag()->getTitle()),array());    
        } else {
            $this->log->info(sprintf('QuickTag:: Failed to remove tag at ID %s with title %s',(string)$event->getRemovedTag()->getTagId(),(string)$event->getRemovedTag()->getTitle()),array());    
        }
    }
    
    /**
      *  Log store event
      *
      *  @access public
      *  @param TagStoreEvent $event 
      */
    public function logStoreEvent(TagStoreEvent $event)
    {
        if($event->getResult() === true) {
            $this->log->info(sprintf('QuickTag:: Stored Tag at ID %s with title %s',(string)$event->getStoredTag()->getTagId(),(string)$event->getStoredTag()->getTitle()),array());    
        } else {
            $this->log->info(sprintf('QuickTag:: Failed to stored Tag at ID %s with title %s',(string)$event->getStoredTag()->getTagId(),(string)$event->getStoredTag()->getTitle()),array());    
        }
    }
   
    
}
/* End of File */