<?php
namespace QuickTag\Model;

use DateTime;
use Zend\Tag\TaggableInterface;
use QuickTag\QuickTagException;


/**
  *  Tag been saved to datastore
  *
  *  @author Lewis Dyer <getintouch@icomefromthenet.com>
  *  @since 0.0.1
  */
class StoredTag implements TaggableInterface
{

     /**
    * Title of the tag
    *
    * @var string
    */
    protected $title = null;

    /**
    * Weight of the tag
    *
    * @var float
    */
    protected $weight = null;

    /**
    * Custom parameters
    *
    * @var string
    */
    protected $params = array();

    /**
      *  @var integer database ID 
      */
    protected $tag_id;
    
    /**
      *  @var DateTime when tag first stored 
      */
    protected $tag_created;
    
    /**
      *  @var mixed the user context 
      */
    protected $user_context;
    
    /**
    * Defined by Zend\Tag\TaggableInterface
    *
    * @return string
    */
    public function getTitle()
    {
        return $this->title;
    }

    /**
    * Set the title
    *
    * @param string $title
    * @throws QuickTag\QickTagException When title is no string
    * @return QuickTag\Model\StoredTag 
    */
    public function setTitle($title)
    {
        if (!is_string($title)) {
            throw new QuickTagException('Title must be a string');
        }

        $this->title = (string) $title;
        return $this;
    }

    /**
    * Defined by Zend\Tag\TaggableInterface
    *
    * @return float
    */
    public function getWeight()
    {
        return $this->weight;
    }

    /**
    * Set the weight
    *
    * @param float $weight
    * @throws QuickTag\QickTagException When weight is not numeric
    * @return QuickTag\Model\StoredTag 
    */
    public function setWeight($weight)
    {
        if (!is_numeric($weight)) {
            throw new QuickTagException('Weight must be numeric');
        }

        $this->weight = (float) $weight;
        return $this;
    }

    /**
    * Defined by Zend\Tag\TaggableInterface
    *
    * @param string $name
    * @param mixed $value
    * @return QuickTag\Model\StoredTag 
    */
    public function setParam($name, $value)
    {
        $this->params[$name] = $value;
        return $this;
    }

    /**
    * Defined by Zend\Tag\TaggableInterface
    *
    * @param string $name
    * @return mixed
    */
    public function getParam($name)
    {
        if (isset($this->params[$name])) {
            return $this->params[$name];
        }
        return null;
    }
    
    /**
      *  Return the tag storage id
      *
      *  @access public
      *  @return integer the storage id
      */
    public function getTagId()
    {
        return $this->tag_id;
    }
    
    /**
      *  Sets the storage id
      *
      *  @access public
      *  @throws QuickTag\QickTagException When id is not integer
      *  @return QuickTag\Model\StoredTag
      */
    public function setTagId($id)
    {
         if (!is_int($id)) {
            throw new QuickTagException('Tag ID must be an integer');
        }

        $this->tag_id = (integer) $id;
        return $this;
    }
    
    /**
      *  Gets the date the tag was first stored
      *
      *  @access public
      *  @return DateTime storage date
      */
    public function getTagCreated()
    {
        return $this->tag_created;
    }
    
    /**
      *  Set the date when tag first stored
      *
      *  @access public
      *  @return QuickTag\Model\StoredTag
      */
    public function setTagCreated(DateTime $now)
    {
        $this->tag_created = $now;
        
        return $this;
    }
    
    /**
      *  Set the tag to a user
      *
      *  @param mixed $context a user identifer 
      *  @access public
      *  @return QuickTag\Model\StoredTag
      */
    public function setUserContext($context)
    {
        $this->user_context = $context;
        
        return $this;
    }
    
    
    /**
      *  Fetch the user context of the tag
      *
      *  @access public
      *  @return mixed the user context
      */
    public function getUserContext()
    {
        return $this->user_context;
    }
    
}
/* End of File */