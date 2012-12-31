<?php
namespace QuickTag\Tests;

use PHPUnit_Framework_TestCase;
use DateTime;
use QuickTag\Model\TagBuilder,
    QuickTag\Model\StoredTag;

/**
  *  Unit Tests for Model Transition Builder and Entity test 
  *
  *  @author Lewis Dyer <getintouch@icomefromthenet.com>
  *  @since 0.0.1
  */
class BuilderTest extends  PHPUnit_Framework_TestCase
{
 
    public function testEntityProperties()
    {
        $tag_id            = 1;
        $tag_user_context  = 3;
        $tag_date_created  = new DateTime();
        $tag_weight        = 3.56;
        $tag_title         = 'finance';
        
        $entity = new StoredTag();
        
        $entity->setTagId($tag_id);
        $entity->setUserContext($tag_user_context);
        $entity->setTagCreated($tag_date_created);
        $entity->setWeight($tag_weight);
        $entity->setTitle($tag_title);
        
        
        $this->assertEquals($tag_id,$entity->getTagId());
        $this->assertEquals($tag_user_context,$entity->getUserContext());
        $this->assertEquals($tag_date_created,$entity->getTagCreated());
        $this->assertEquals($tag_weight,$entity->getWeight());
        $this->assertEquals($tag_title,$entity->getTitle());
        
    }
 
    /**
      *  @expectedException \QuickTag\QuickTagException
      *  @expectedExceptionMessage Tag ID must be an integer
      */
    public function testEntityExceptionTagIdIsString()
    {
        
        $entity = new StoredTag();
        $entity->setTagId('aaa');
        
    }
    
    
    /**
      *  @expectedException \QuickTag\QuickTagException
      *  @expectedExceptionMessage Tag ID must be an integer
      */
    public function testEntityExceptionTagIdIsNull()
    {
        $entity = new StoredTag();
        $entity->setTagId('aaa');
        
    }
    
    
    /**
      *  @expectedException \QuickTag\QuickTagException
      *  @expectedExceptionMessage Weight must be numeric
      */
    public function testEntityExceptionWeightIsString()
    {
        
        $entity = new StoredTag();
        $entity->setWeight('aaa');
        
    }
    
    
    /**
      *  @expectedException \QuickTag\QuickTagException
      *  @expectedExceptionMessage Weight must be numeric
      */
    public function testEntityExceptionWeightIsNull()
    {
        $entity = new StoredTag();
        $entity->setWeight(null);
        
    }
 
     /**
      *  @expectedException \QuickTag\QuickTagException
      *  @expectedExceptionMessage Title must be a string
      */
    public function testEntityExceptionTitleIsNumeric()
    {
        
        $entity = new StoredTag();
        $entity->setTitle(2234);
        
    }
    
    
    /**
      *  @expectedException \QuickTag\QuickTagException
      *  @expectedExceptionMessage Title must be a string
      */
    public function testEntityExceptionTitleIsNull()
    {
        $entity = new StoredTag();
        $entity->setTitle(null);
        
    }
 
 
    public function testEntityBuild()
    {
        $tag_id            = 1;
        $tag_user_context  = 3;
        $tag_date_created  = new DateTime();
        $tag_weight        = 3.56;
        $tag_title         = 'finance';
        
        $data = array(
            'tag_id'           => $tag_id,
            'tag_user_context' => $tag_user_context,
            'tag_date_created' => $tag_date_created,
            'tag_weight'       => $tag_weight,
            'tag_title'        => $tag_title,
        );
        
        $builder = new TagBuilder();
        
        $entity = $builder->build($data);
        
        $this->assertEquals($tag_id,$entity->getTagId());
        $this->assertEquals($tag_user_context,$entity->getUserContext());
        $this->assertEquals($tag_date_created,$entity->getTagCreated());
        $this->assertEquals($tag_weight,$entity->getWeight());
        $this->assertEquals($tag_title,$entity->getTitle());
        
    }
 
 
    public function testEntityDemolish()
    {
        $tag_id            = 1;
        $tag_user_context  = 3;
        $tag_date_created  = new DateTime();
        $tag_weight        = 3.56;
        $tag_title         = 'finance';
        
        $data = array(
            'tag_id'           => $tag_id,
            'tag_user_context' => $tag_user_context,
            'tag_date_created' => $tag_date_created,
            'tag_weight'       => $tag_weight,
            'tag_title'        => $tag_title,
        );
        
        $builder = new TagBuilder();
        $entity = new StoredTag();
        
        $entity->setTagId($tag_id);
        $entity->setUserContext($tag_user_context);
        $entity->setTagCreated($tag_date_created);
        $entity->setWeight($tag_weight);
        $entity->setTitle($tag_title);
        
        
        $this->assertEquals($data,$builder->demolish($entity));
        
    }
    
    
}
/* End of File */