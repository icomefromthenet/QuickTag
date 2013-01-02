<?php
namespace QuickTag\Model;

use DBALGateway\Query\AbstractQuery;
use DateTime;
use Doctrine\DBAL\Query\Expression\CompositeExpression;

/**
* Query class for Tags
*
* @author Lewis Dyer <getintouch@icomefromthenet.com>
* @since 0.0.1
*/
class TagQuery extends AbstractQuery
{
    
    /**
    * Filter by the storage id
    *
    * @access public
    * @return TagQuery
    * @param integer $id of the tag
    */
    public function filterById($id)
    {
        $this->andWhere($this->expr()->eq('tag_id',':tag_id'))->setParameter('tag_id',$id,$this->getGateway()->getMetaData()->getColumn('tag_id')->getType());
        
        return $this;
    }

    /**
    * Filter by the user context
    *
    * @access public
    * @return TagQuery
    * @param mixed $context the user context 
    */
    public function filterByUserContext($context)
    {
        $this->andWhere($this->expr()->eq('tag_user_context',':tag_user_context'))->setParameter('tag_user_context',$context,$this->getGateway()->getMetaData()->getColumn('tag_user_context')->getType());
        
        return $this;
    }
    
    
    public function filterByNameEqual($name)
    {
         $this->andWhere($this->expr()->eq('tag_title',':tag_title'))->setParameter('tag_title',$name,$this->getGateway()->getMetaData()->getColumn('tag_title')->getType());
        
        return $this;
    }
    
    
    public function filterByNameStartsWith($start)
    {
        $this->andWhere($this->expr()->like('tag_title',':tag_title_start'))->setParameter('tag_title_start',$start.'%',$this->getGateway()->getMetaData()->getColumn('tag_title')->getType());
        
        return $this;
    }
    
    /**
    * Filter tranistions to created before x
    *
    * @access public
    * @return TagQuery
    * @param DateTime $before
    */
    public function filterCreatedBefore(DateTime $before)
    {
        
        $this->andWhere($this->expr()->lte('tag_date_created',':dte_created_before'))->setParameter('dte_created_before',$before,$this->getGateway()->getMetaData()->getColumn('tag_date_created')->getType());
        
        return $this;
    }
    
    /**
    * Filter tranistions to created after x
    *
    * @access public
    * @return TagQuery
    * @param DateTime $after
    */
    public function filterCreatedAfter(DateTime $after)
    {
        
        $this->andWhere($this->expr()->gte('tag_date_created',':dte_created_after'))->setParameter('dte_created_after',$after,$this->getGateway()->getMetaData()->getColumn('tag_date_created')->getType());
        
        return $this;
    }
    
     
    /**
    * Sort the result by the created date
    *
    * @access public
    * @param string direction ASC|DESC
    * @return TagQuery
    */
    public function orderByCreated($direction = 'ASC')
    {
        $this->orderBy('tag_date_created',$direction);
        
        return $this;
    }
    
    /**
    * Sort the result by the tag weight
    *
    * @access public
    * @param string direction ASC|DESC
    * @return TagQuery
    */
    public function orderByWeight($direction = 'ASC')
    {
        $this->orderBy('tag_weight',$direction);
        
        return $this;
    }
    
    /**
    * Sort the result by the tag title 
    *
    * @access public
    * @param string direction ASC|DESC
    * @return TagQuery
    */
    public function orderByTitle($direction = 'ASC')
    {
        $this->orderBy('tag_title',$direction);
        
        return $this;
    }
    
    /**
    * Group the query by the tage title
    *
    * @access public
    * @return TagQuery
    */
    public function groupByTitle()
    {
        $this->groupBy('tag_title');
        
        return $this;    
    }
    
    /**
    * Group the query by the User Context
    *
    * @access public
    * @return TagQuery
    */
    public function groupByUserContext()
    {
        $this->groupBy('tag_user_context');
        
        return $this;   
    }

    /**
      *  Will return a count column using tag_id
      *
      *  Must be used with groupBy statement.
      *
      *  @return TagQuery
      *  @access public
      */
    public function calculateTagCount()
    {
        $platform = $this->getConnection()->getDatabasePlatform()->getCountExpression('tag_id');
        $this->addSelect($platform .' AS ' . 'tag_count');
        
        return $this;
    }
}
/* End of File */