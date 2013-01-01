<?php
namespace QuickTag\Model;

use DateTime;
use DBALGateway\Exception as DBALGatewayException;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use QuickTag\QuickTagException;

/**
  *  Mapper To Tag Entity
  *
  *  @author Lewis Dyer <getintouch@icomefromthenet.com>
  *  @since 0.0.1
  */
class TagMapper
{
    /**
      *  @var QuickTag\Model\TagGateway 
      */
    protected $gateway;
    
    /**
      *  @var  Symfony\Component\EventDispatcher\EventDispatcherInterface
      */
    protected $event;
    
    /**
      *  Create a tag
      *
      *  @access protected
      *  @return boolean the result true if removed
      *  @param QuickTag\Model\StoredTag $tag
      *  @throws QuickTag\QuickTagException if database operation fails
      */
    protected function createTag(StoredTag $tag)
    {
        $result = null;
        
        try {
            
                if($tag->getTagCreated() === null) {
                    $tag->setTagCreated(new DateTime());
                }
                
                # new tag store it
                $result = $this->gateway->insertQuery()
                            ->start()
                                ->addColumn('tag_title',$tag->getTitle())
                                ->addColumn('tag_weight',$tag->getWeight())
                                ->addColumn('tag_user_context',$tag->getUserContext())
                                ->addColumn('tag_date_created',$tag->getTagCreated())
                            ->end()
                        ->insert();
                
                if($result) {
                    $tag->setTagId((integer)$this->gateway->lastInsertId());
                }
                
                # normalise the return
                if($result === null) {
                    $result = false;
                }
                
            } catch(DBALGatewayException $exception) {
                throw new QuickTagException($exception->getMessage(),0,$exception);
            }
            
        return $result;
    }
    
    /**
      *  Update a tag
      *
      *  @access protected
      *  @return boolean the result true if removed
      *  @param QuickTag\Model\StoredTag $tag
      *  @throws QuickTag\QuickTagException if database operation fails
      */
    protected function updateTag(StoredTag $tag)
    {
        $result = null;
        
        try {
                $result = $this->gateway->updateQuery()
                        ->start()
                            ->addColumn('tag_title',$tag->getTitle())
                            ->addColumn('tag_weight',$tag->getWeight())
                            ->addColumn('tag_user_context',$tag->getUserContext())
                        ->where()
                            ->filterById($tag->getTagId())
                        ->end()
                    ->update();
                
                # normalise the return
                if($result === null) {
                    $result = false;
                }
                
            } catch(DBALGatewayException $exception) {
                throw new QuickTagException($exception->getMessage(),0,$exception);
            }
            
        return $result;
        
    }
    
    
    /**
      *  Class Constructor
      *
      *  @param Symfony\Component\EventDispatcher\EventDispatcherInterface $event
      *  @param QuickTag\Model\TagGateway $gateway
      */
    public function __construct(EventDispatcherInterface $event, TagGateway $gateway)
    {
        $this->gateway = $gateway;
        $this->event   = $event;
    }
    
        
    /**
      *  Store a new or existing tag
      *
      *  @access public
      *  @return boolean the result true if sucessful
      *  @param QuickTag\Model\StoredTag $tag
      *  @throws QuickTag\QuickTagException if database operation fails
      */
    public function save(StoredTag $tag)
    {
        $result = null;
        
        if($tag->getTagId() === null) {
            $result = $this->createTag($tag);
        } else {
            $result = $this->updateTag($tag);
        }
        
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
    public function delete(StoredTag $tag)
    {
        $result = null;
        
        if($tag->getTagId() === null) {
            throw new QuickTagException('Given tag does not have a database id assigned can not delete');
        } else {
            
            $result = $this->gateway->deleteQuery()
                           ->start()
                                ->filterById($tag->getTagId())
                           ->end()
                      ->delete();
            
            # reset the tag id          
            if($result) {
                $tag->setTagId(0);
            }
        }
        
        return $result;
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
    public function findByID($id)
    {
        $result = null;
     
        try {
            
            $result = $this->gateway->selectQuery()
                ->start()
                    ->filterById($id)
                ->end()
            ->findOne();
        
        } catch(DBALGatewayException $exception){
            throw new QuickTagException($exception->getMessage(),0,$exception);            
        }
            
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
    public function find()
    {
        return $this->gateway->selectQuery();        
    }
    
        
}
/* End of File */