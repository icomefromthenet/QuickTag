<?php
namespace QuickTag\Model;

use DateTime;
use DBALGateway\Table\AbstractTable,
    DBALGateway\Exception as DBALGatewayException;
use QuickTag\QuickTagException,
    QuickTag\Events\TagEventsMap,
    QuickTag\Events\TagLookupEvent;
use Doctrine\Common\Collections\Collection,
    Doctrine\Common\Collections\ArrayCollection;

/**
* Table Gateway for tag storage
*
* @author Lewis Dyer <getintouch@icomefromthenet.com>
* @since 0.0.1
*/
class TagGateway extends AbstractTable
{
    
    
    /**
    * Create a new instance of the querybuilder
    *
    * @access public
    * @return QuickTag\Model\TagQuery
    */
    public function newQueryBuilder()
    {
        return new TagQuery($this->getAdapater(),$this);
    }
    
    
    public function findOne()
    {
        $result = parent::findOne();
        $collection = new ArrayCollection();
        
        if(!$result instanceof StoredTag) {
            $collection->add($result);
        }
        
        $this->event_dispatcher->dispatch(TagEventsMap::LOOKUP,new TagLookupEvent($collection));
        
        return $result;
    }
    
    
    public function find()
    {
        $result = parent::find();
        
        if(!$result instanceof Collection) {
            $result = new ArrayCollection();
        }
        
        $this->event_dispatcher->dispatch(TagEventsMap::LOOKUP,new TagLookupEvent($result));
        
        return $result;
    }
    
    
}
/* End of File */