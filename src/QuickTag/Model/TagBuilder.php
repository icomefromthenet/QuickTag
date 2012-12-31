<?php
namespace QuickTag\Model;

use DBALGateway\Builder\BuilderInterface;

/**
* Builds Stored Tags into entities
*
* @author Lewis Dyer <getintouch@icomefromthenet.com>
* @since 0.0.1
*/
class TagBuilder implements BuilderInterface
{
    
    /**
    * Convert data array into entity
    *
    * @return QuickTag\Model\StoredTag
    * @param array $data
    * @access public
    */
    public function build($data)
    {
        $object = new StoredTag();
        
        if($data['tag_user_context'] !== null) {
            $object->setUserContext($data['tag_user_context']);
        }
        
        if($data['tag_weight'] !== null) {
          $object->setWeight($data['tag_weight']);  
        }
        
        $object->setTagId($data['tag_id']);
        $object->setTagCreated($data['tag_date_created']);
        $object->setTitle($data['tag_title']);
        
        return $object;
    }
    
    /**
    * Convert and entity into a data array
    *
    * @return array
    * @access public
    */
    public function demolish($entity)
    {
        return array(
            'tag_id'           => $entity->getTagId(),
            'tag_user_context' => $entity->getUserContext(),
            'tag_title'        => $entity->getTitle(),
            'tag_weight'       => $entity->getWeight(),
            'tag_date_created' => $entity->getTagCreated()
        );
        
    }
    
}
/* End of File */