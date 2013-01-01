<?php
namespace QuickTag\Silex\Formatter;

use QuickTag\Model\StoredTag;
use Doctrine\Common\Collections\Collection;

class TagFormatter
{
    public function toArray(StoredTag $entity)
    {
        return array(
            'tagId'      => $entity->getTagId(),
            'tagCreated' => $entity->getTagCreated(),
            'tagTitle'   => $entity->getTitle(),
            'tagWeight'  => $entity->getWeight(),
            'tagUserContext' => $entity->getUserContext() 
        );
    }
    
    public function toArrayCollection(Collection $col)
    {
        $results = array();
        
        foreach($col as $item) {
            $results[] = $this->toArray($item);
        }
        
        return $results;
    }
}
/* End of File */