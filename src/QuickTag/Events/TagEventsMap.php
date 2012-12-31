<?php
namespace QuickTag\Events;

/**
  *  Map of all events that Tag Library Emit
  *
  *  @author Lewis Dyer <getintouch@icomefromthenet.com>
  *  @since 0.0.1
  */
final class TagEventsMap
{
    /**
     * The quicktag.store event is thrown each time a tag is stored (new and update)
     *
     * The event listener receives QuickTag\Events\TagStoreEvent
     * instance.
     *
     * @var string
     */
   const STORE  = 'quicktag.store';
   
   
    /**
     * The quicktag.remove event is thrown each time a tag is removed
     *
     * The event listener receives QuickTag\Events\TagRemoveEvent
     * instance.
     *
     * @var string
     */
   const REMOVE  = 'quicktag.remove';
   
   
    /**
     * The quicktag.lookup event is thrown each time a tag is queried (bulk or sing)
     *
     * The event listener receives QuickTag\Events\TagLookupEvent
     * instance.
     *
     * @var string
     */
   const LOOKUP  = 'quicktag.lookup';
   
    
}
/* End of File */